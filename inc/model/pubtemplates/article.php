<?php

/**
 * Public article template file object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Article Template Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class article extends template {

    use \fpcm\model\traits\pubTemplateArticles;

    const TEMPLATE_ID = 'article';

    const TEMPLATE_ID_SINGLE = 'articleSingle';

    const PAGEBREAK_TAG = '<!-- pagebreak -->';

    const GALLERY_TAG_START = '[gallery]{{IMAGES}}';

    const GALLERY_TAG_END = '[/gallery]';

    const GALLERY_TAG_THUMB = 'thumb:';

    const GALLERY_TAG_LINK = ':link';

    /**
     * Template-Platzhalter
     * @var array
     */
    protected $replacementTags = array(
        '{{headline}}' => '',
        '{{text}}' => '',
        '{{textShort}}' => '',
        '{{author}}' => '',
        '{{authorEmail}}' => '',
        '{{authorInfoText}}' => '',
        '{{authorAvatar}}' => '',
        '{{date}}' => '',
        '{{changeDate}}' => '',
        '{{changeUser}}' => '',
        '{{statusPinned}}' => '',
        '{{shareButtons}}' => '',
        '{{categoryIcons}}' => '',
        '{{categoryTexts}}' => '',
        '{{commentCount}}' => '',
        '{{permaLink}}:{{/permaLink}}' => '',
        '{{commentLink}}:{{/commentLink}}' => '',
        '{{articleImage}}' => '',
        '{{sources}}' => ''
    );

    /**
     * Interne Platzhalter
     * @var array
     */
    protected $replacementInternal = array(
        '<readmore>:</readmore>'
    );

    /**
     * Kommentar-Parsner aktiv
     * @var bool
     */
    protected $commentsEnabled = true;

    /**
     * Tag-Kombinationen, die beseitigt werden müssen
     * @var array
     */
    protected $cleanups = array(
        '<p><readmore>' => '<readmore>',
        '</readmore></p>' => '</readmore>'
    );

    /**
     * List of attributes by replacement tag
     * @var array
     * @since FPCM 4.1
     */
    protected $replacementAttributesMap = [
        '{{sources}}' => ['descr', 'descrAlt', 'hideEmpty']
    ];

    /**
     * Konstruktor
     * @param string $fileName Template-Datei unterhalb von data/styles/articles
     */
    public function __construct($fileName)
    {
        parent::__construct('articles' . DIRECTORY_SEPARATOR . $fileName . '.html');
        $this->allowedTags[] = '<header>';
        $this->allowedTags[] = '<article>';
        $this->allowedTags[] = '<section>';
        $this->allowedTags[] = '<footer>';
    }

    /**
     * Parst Template-Platzhalter
     * @return bool
     */
    public function parse()
    {
        if (!count($this->replacementTags) || !$this->content) {
            return false;
        }

        $this->replacementTags = $this->events->trigger('template\parseArticle', $this->replacementTags);
        $tags = array_merge($this->replacementInternal, $this->replacementTags);
        
        $replacementData = [];
        $replacementData1 = [];

        foreach ($tags as $replacement => $value) {

            $splitTags = explode(':', $replacement);

            if ($splitTags[0] === '<readmore>') {
                $replacementData[$splitTags[0]] = '<a href="#" class="fpcm-pub-readmore-link" id="' . $value . '">' . $this->language->translate('ARTICLES_PUBLIC_READMORE') . '</a><div class="fpcm-pub-readmore-text" id="fpcm-pub-readmore-text-' . $value . '">';
                $replacementData[$splitTags[1]] = '</div>';
                continue;
            }

            $replacementDataResult = [];
            $this->parseTag($splitTags[0], $value, $replacementDataResult, $replacement);

            if (!count($replacementDataResult)) {
                $replacementData[$splitTags[0]] = $value;
            }
            
            foreach ($replacementDataResult as $idx => $newVal) {
                
                if (!is_int($idx)) {
                    $replacementData[$idx] = $newVal;
                    continue;
                }
                
                $replacementData[$splitTags[$idx]] = $replacementDataResult[$idx];
            }

        }

        return $this->parseSmileys($this->parseGallery(str_replace(
            array_keys($replacementData),
            $this->cleanup(array_values($replacementData)),
            $this->content
        )));
    }

    /**
     * Kommentar-Parser aktivieren
     * @param bool $commentsEnabled
     */
    public function setCommentsEnabled($commentsEnabled)
    {
        $this->commentsEnabled = $commentsEnabled;
    }

    /**
     * Assigns template variables by object of type @see \fpcm\model\articles\article
     * @param \fpcm\model\articles\article $article
     * @param array $users
     * @param array $categories
     * @param int $commentCount
     * @return bool
     */
    public function assignByObject(\fpcm\model\articles\article $article, array $users, array $categories, $commentCount)
    {
        if (!isset($users['author']) || !isset($users['changeUser'])) {
            trigger_error('Invalid user data, "author" or "changeUser" missing');
            return false;
        }

        $notFoundStr = $this->language->translate('GLOBAL_NOTFOUND');
        /* @var $share sharebuttons */
        $share = \fpcm\classes\loader::getObject('\fpcm\model\pubtemplates\sharebuttons');
        $share->assignData($article->getElementLink(), $article->getTitle(), $article->getId());

        $this->data['tmpLink'] = $article->getElementLink();
        
        $this->setReplacementTags([
            '{{headline}}' => $article->getTitle(),
            '{{text}}' => $article->getContent(),
            '{{textShort}}' => $article->getContent(),
            '{{date}}' => date($this->config->system_dtmask, $article->getCreatetime()),
            '{{statusPinned}}' => $article->getPinned() ? $this->language->translate('PUBLIC_ARTICLE_PINNED') : '',
            '{{shareButtons}}' => $share->parse(),
            '{{commentCount}}' => $this->config->system_comments_enabled && $article->getComments() ? (int) $commentCount : 0,
            '{{author}}' => $users['author'] ? $users['author']->getDisplayname() : $notFoundStr,
            '{{authorEmail}}' => ($users['author'] ? '<a href="mailto:' . $users['author']->getEmail() . '">' . $users['author']->getDisplayname() . '</a>' : ''),
            '{{authorAvatar}}' => $users['author'] ? \fpcm\model\users\author::getAuthorImageDataOrPath($users['author'], 0) : '',
            '{{authorInfoText}}' => $users['author'] ? nl2br($users['author']->getUsrinfo(), false) : '',
            '{{changeDate}}' => date($this->config->system_dtmask, $article->getChangetime()),
            '{{changeUser}}' => $users['changeUser'] ? $users['changeUser']->getDisplayname() : $notFoundStr,
            '{{categoryIcons}}' => implode(PHP_EOL, array_values($categories)),
            '{{categoryTexts}}' => implode(PHP_EOL, array_keys($categories)),
            '{{permaLink}}:{{/permaLink}}' => $article->getElementLink(),
            '{{commentLink}}:{{/commentLink}}' => $article->getElementLink('#comments'),
            '<readmore>:</readmore>' => $article->getId(),
            '{{articleImage}}' => $article->getArticleImage(),
            '{{sources}}' => $article->getSources()
        ]);
        
        return true;
    }

    /**
     * Cleanup tags
     * @param string $values
     * @return string
     */
    protected function cleanup($values)
    {
        return str_replace(array_keys($this->cleanups), array_values($this->cleanups), $values);
    }

    /**
     * Fetch and replace tag attributes
     * @param string $tag
     * @param mixed $value
     * @param array $replacementData
     * @param callable $callback
     * @return bool
     * @since FPCM 4.1
     */
    private function processAttributes(string $tag, $value, array &$replacementData, callable $callback) : bool
    {
        $attributes = $this->parseAttributes($tag);
        if (!count($attributes)) {
            return false;
        }

        foreach ($attributes as $tag => $attr) {
            $replacementData[$tag] = call_user_func($callback, $attr, $value, '');
        }

        return true;
    }

    /**
     * Parse short text tag
     * @param mixed $value
     * @param array $return
     * @since FPCm 4.4
     */
    protected function parseTextShort($value, array &$return)
    {
        $pos = strpos( $value, self::PAGEBREAK_TAG);
        if (!$pos) {
            return true;
        }
        
        $return[0] = substr( $value, 0, $pos ) .
                    "<a href=\"{$this->data['tmpLink']}\" class=\"fpcm-pub-pagebreak-link\">".
                    $this->language->translate('ARTICLES_PUBLIC_READMORE') .
                    '</a>';

        return true;
    }

    /**
     * Parse comment link tag
     * @param mixed $value
     * @param array $return
     * @since FPCm 4.4
     */
    protected function parseSources($value, array &$return)
    {
        $this->parseLinks($value, [
            'rel' => 'noopener noreferrer,external',
            'target' => '_blank',
        ]);

        $this->processAttributes('sources', $value, $return, function($attr, $value, $newVal) {

            $isEmpty = trim($value) ? false : true;
            if (isset($attr['hideEmpty']) && $isEmpty) {
                return '';
            }

            if (isset($attr['descr']) && trim($attr['descr'])) {
                $newVal .= $attr['descr'];
            }

            if (isset($attr['descrAlt']) && trim($attr['descrAlt']) && $isEmpty) {
                $newVal .= $attr['descrAlt'];
            }

            return $newVal.$value;
        });

    }

    /**
     * Parse gallery tag
     * @param string $content
     * @return string
     * @since FPCM 4.4
     */
    protected function parseGallery(string $content) : string
    {
        $regex = '/(\[gallery\])(.*)(\[\/gallery\])/i';
        if (preg_match($regex, $content, $matches) === false) {
            return $content;
        }

        $images = $matches[2] ?? [];
        if (!count($images)) {
            return $content;
        }

        $w = $this->config->file_img_thumb_width;
        $h = $this->config->file_img_thumb_height;

        $thumbLen = 6;
        $linkLen = -5;
        
        $data = array_map(function ($fileName) use ($w, $h, $thumbLen, $linkLen)
        {
            $isThumb = (substr($fileName, 0, $thumbLen) === self::GALLERY_TAG_THUMB);
            $isLink = (substr($fileName, $linkLen) === self::GALLERY_TAG_LINK);

            $imgObj = new \fpcm\model\files\image(
                substr(
                    $fileName,
                    ($isThumb ? $thumbLen : 0),
                    ($isLink ? $linkLen : NULL)
                ),
                false
            );

            $url = $isThumb ? $imgObj->getThumbnailUrl() : $imgObj->getImageUrl();
            $whStr = $isThumb ? "width=\"{$w}\" height=\"{$h}\"" : $imgObj->getWhstring();

            $imgTag = "<img src=\"{$url}\" {$whStr} alt=\"{$imgObj->getFilename()}\" class=\"fpcm pub-content pub-gallery-image\">";
            if (!$isLink) {
                return $imgTag;
            }

            return "<a class=\"fpcm pub-content pub-gallery-link\" href=\"{$imgObj->getImageUrl()}\">{$imgTag}</a>";

        }, explode('|', $images));

        return preg_replace($regex, "<div class=\"fpcm pub-content pub-gallery\">".implode("\n", $data)."</div>", $content);
    }

}

?>