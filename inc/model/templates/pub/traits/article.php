<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub\traits;


/**
 * Assign article data to template trait
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait article {    

    /* Page break tag, replaces <readmore< block */
    const PAGEBREAK_TAG = '<!-- pagebreak -->';

    /* Image gallery: start tag */
    const GALLERY_TAG_START = '[gallery]{{IMAGES}}';

    /* Image gallery: end tag */
    const GALLERY_TAG_END = '[/gallery]';

    /* Image gallery: thumbnail attribute */
    const GALLERY_TAG_THUMB = 'thumb:';

    /* Image gallery: link attribute */
    const GALLERY_TAG_LINK = ':link';

    /**
     * Assign article data
     * @param \fpcm\model\articles\article $article
     * @param \fpcm\model\users\author|null $author
     * @param \fpcm\model\users\author|null $changeUser
     * @param array $categories
     * @param int $commentCount
     * @return array
     */
    private function apply(
        \fpcm\model\articles\article $article,
        ?\fpcm\model\users\author $author,
        ?\fpcm\model\users\author $changeUser,
        array $categories,
        int $commentCount,
    ): array
    {
        
        $content = $article->getContent();        
        $content = $this->parseLinks($content);
        $content = $this->parseGallery($content);
        
        return [
            'id' => $article->getId(),
            'headline' => $article->getTitle(),
            'text' => $content,
            'textShort' => $this->parseTextShort($content, $article->getElementLink()),
            'date' => date($this->getConfig()->system_dtmask, $article->getCreatetime()),
            'statusPinned' => $article->getPinned(),
            'commentCount' => $commentCount,
            'commentsActive' => $article->getComments(),
            'author' => $author ? $author->getDisplayname() : '',
            'authorEmail' => ($author ? $author->getEmail() : ''),
            'authorAvatar' => $author ? \fpcm\model\users\author::getAuthorImageDataOrPath($author, false) : '',
            'authorInfoText' => $author ? nl2br($author->getUsrinfo(), false) : '',
            'changeDate' => date($this->getConfig()->system_dtmask, $article->getChangetime()),
            'changeUser' => $changeUser ? $changeUser->getDisplayname() : '',
            'categoryIcons' => array_values($categories),
            'categoryTexts' => array_keys($categories),
            'permaLink' => $article->getElementLink(),
            'commentLink' => $article->getElementLink('#comments'),
            'articleImage' => $article->getArticleImage(),
            'sources' => $article->getSources(),
            'oldarticle' => $article->isOldArticle(),
            'editLink' => $article->getEditLink()
        ];
    }

    /**
     * Event directly before render processes
     * @param \Twig\Environment $twig
     * @return bool
     */
    #[\Override]
    public function onRender(\Twig\Environment &$twig): bool
    {
        $twig->addFunction(new \Twig\TwigFunction(
            'share_buttons',
            function (string $description, string $item_link) {
                return $this->getShareLinkItems($description, $item_link);
            }
        ));
        
        $twig->addFunction(new \Twig\TwigFunction(
            'share_button_icon',
            function (string $icon) {
                return \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_SHARE, $icon);
            }
        ));

        $twig->addFunction(new \Twig\TwigFunction(
            'get_pager',
            function (array $pager) {

                list($items, $perPage, $current, $archive, $action) = $pager;

                $count = ceil($items / $perPage);
                if (!$count) {
                    return [];
                }

                $result = [
                    'pages' => [],
                    'next' => $current < $count ? sprintf('%s?module=%s&page=%d', $this->getConfig()->system_url, $action, $current + 1) : '',
                    'previous' => $current > 1 ? sprintf('%s?module=%s&page=%d', $this->getConfig()->system_url, $action, $current - 1) : '',
                    'archive' => $archive ? sprintf('%s?module=fpcm/archive', $this->getConfig()->system_url) : ''
                ];

                foreach (array_fill(1, $count, []) as $key => &$value) {

                    $result['pages'][] = [
                        'label' => $key,
                        'class' => $key == $current || ($key == 1 && !$current) ? 'fpcm-pub-pagination-page-active' : '',
                        'link'  => $key >= 2
                                ? sprintf('%s?module=%s&page=%d', $this->getConfig()->system_url, $action, $key)
                                : sprintf('%s?module=%s', $this->getConfig()->system_url, $action)
                    ];
                }

                return $result;
            }
        ));

        return true;
    }

    /**
     * Fetch permission getter
     * @return array
     */
    #[\Override]
    public function fetchPermissions(): array
    {
        return [
            'can_add' => $this->getPermissions()->article?->add,
            'can_edit' => $this->getPermissions()->editArticles()
        ];
    }

    /**
     * Apply comments enabled settings
     * @return void
     */
    final protected function applyCommentsEnabled(): void
    {
        $this->vars[self::VAR_COMMENTS_ACTIVE] = $this->getConfig()->system_comments_enabled;
    }

    /**
     * Parse gallery tag
     * @param string $content
     * @return string
     */
    protected function parseGallery(string $content) : string
    {
        $regex = '/(\[gallery\])(.*)(\[\/gallery\])/i';
        if (preg_match($regex, $content, $matches) === false) {
            return $content;
        }

        $images = explode('|', ( $matches[2] ?? '' ) );
        if (!count($images)) {
            return $content;
        }

        $w = $this->getConfig()->file_thumb_size;
        $h = $this->getConfig()->file_thumb_size;

        $thumbLen = 6;
        $linkLen = -5;

        $data = array_map(function ($fileName) use ($w, $h, $thumbLen, $linkLen)
        {
            $isThumb = (substr($fileName, 0, $thumbLen) === self::GALLERY_TAG_THUMB) ? true : false;
            $isLink = (substr($fileName, $linkLen) === self::GALLERY_TAG_LINK) ? true : false;

            $imgObj = new \fpcm\model\files\mediaFile(
                substr(
                    $fileName,
                    ($isThumb ? $thumbLen : 0),
                    ($isLink ? $linkLen : strlen($fileName))
                ),
                false
            );

            $url = $isThumb ? $imgObj->getThumbnailUrl() : $imgObj->getImageUrl();
            $whStr = $isThumb ? '' : $imgObj->getWhstring();

            $imgTag = sprintf(
                '<img loading="lazy" %s src="%s" alt="%s" class="fpcm-pub-content-gallery-image">',
                $whStr,
                $url,
                $imgObj->getFilename()
            );
            
            if (!$isLink) {
                return $imgTag;
            }

            return sprintf('<a class="fpcm-pub-content-gallery-link" href="%s">%s</a>',
                $imgObj->getImageUrl(),
                $imgTag
            );

        }, $images);

        return preg_replace($regex, "<figure role=\"group\" class=\"fpcm-pub-content-gallery\">".implode("\n", $data)."</figure>", $content);
    }

    /**
     * Parse short text tag
     * @param string $value
     * @param string $link
     * @return string
     */
    protected function parseTextShort(string $value, string $link) : string
    {
        $pos = strpos( $value, self::PAGEBREAK_TAG);
        if (!$pos) {
            return $value;
        }

        return sprintf(
            '%s <a href="%s" class="fpcm-pub-pagebreak-link">%s</a>',
            substr($value, 0, $pos),
            $link,
            $this->getLanguage()->translate('ARTICLES_PUBLIC_READMORE')
        );
    }

    /**
     * Parse links in article text
     * @param string $content
     * @param array $attributes
     * @param bool $returnOnly
     * @return string
     */
    protected function parseLinks(string $content, array $attributes = []) : string
    {
        $attrs = '';
        foreach ($attributes as $attribute => $value) {
            $attrs .= " {$attribute}=\"{$value}\"";
        }

        $regEx = '/((http|https?):\/\/\S+[^\s.,>)\]\"\'<\/])/i';

        $content = preg_replace($regEx, "<a href=\"$0\"{$attrs}>$0</a>", $content);

        return $content;
    }
}
