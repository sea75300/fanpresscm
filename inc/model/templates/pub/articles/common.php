<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub\articles;


/**
 * Assign article data to template trait
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait common {

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
        return [
            'id' => $article->getId(),
            'headline' => $article->getTitle(),
            'text' => $article->getContent(),
            'textShort' => $article->getContent(),
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
        $twig->addFunction(new \Twig\TwigFunction('share_buttons',
            function (string $description, string $item_link) {
                return $this->getShareLinkItems($description, $item_link);
            }
        ));
        
        $twig->addFunction(new \Twig\TwigFunction('share_button_icon', function (string $icon) {
            return \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_SHARE, $icon);
        }));

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
            'can_add' => $this->getPermissions()->article->add,
            'can_edit' => $this->getPermissions()->editArticles()
        ];
    }

    /**
     * Apply comments enabled settings
     * @return void
     */
    final protected function applyCommentsEnabled(): void
    {
        $this->vars = [self::VAR_COMMENTS_ACTIVE] = $this->getConfig()->system_comments_enabled;
    }

}
