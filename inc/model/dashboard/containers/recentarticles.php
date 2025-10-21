<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard\containers;

/**
 * Recent articles dashboard container object
 *
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class recentarticles
extends \fpcm\model\dashboard\types\dataview
implements \fpcm\model\interfaces\isAccessible {

    /**
     * Permissions-Objekt
     * @var \fpcm\model\permissions\permissions
     */
    protected $permissions = null;

    /**
     * aktueller Benutzer
     * @var int
     */
    protected $currentUser = 0;

    /**
     * Benutzer ist Admin
     * @see \fpcm\model\abstracts\dashcontainer
     * @var int
     */
    protected $isAdmin = false;

    /**
     * Container is accessible
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->editArticles();
    }

    /**
     * Returns container name
     * @return string
     */
    public function getName()
    {
        return 'recentarticles';
    }

    /**
     * Returns cols
     * @return array
     */
    public function getCols(): array
    {
        return [
            'open',
            'edit',
            'headline',
            'status'
        ];
    }

    /**
     * Returns rows
     * @return array
     */
    public function getRows(): array
    {
        $conditions = new \fpcm\model\articles\search();
        $conditions->limit = [10, 0];
        $conditions->orderby = ['createtime DESC'];

        $articles = (new \fpcm\model\articles\articlelist())->getArticlesByCondition($conditions);
        if (!count($articles)) {
            return [];
        }

        $rows = [];

        $createStr = $this->language->translate('GLOBAL_AUTHOR_EDITOR');

        /* @var $article \fpcm\model\articles\article */
        foreach ($articles as $article) {

            $headline = sprintf(
                '<strong>%s</strong><br><span class="text-secondary">%s : %s</span>',
                new \fpcm\view\helper\escape(strip_tags(rtrim($article->getTitle(), '.!?'))),
                $createStr,
                $this->language->translate('GLOBAL_USER_ON_TIME', array(
                    '{{username}}' => \fpcm\classes\tools::userId2Text($article->getCreateuser()),
                    '{{time}}' => date($this->config->system_dtmask, $article->getCreatetime())
                ))
            );

            $rows[] = [
                'open' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: (new \fpcm\view\helper\openButton('openBtn'))->setUrlbyObject($article)->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW),
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_LINK,
                    class: 'list-group-item-primary'
                ),
                'edit' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: (new \fpcm\view\helper\editButton('editBtn'))->setUrlbyObject($article)->setReadonly(!$article->getEditPermission()),
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_LINK,
                    class: 'list-group-item-secondary'
                ),
                'headline' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: $headline,
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_TEXT,
                    class: 'fpcm-ui-font-small text-truncate'
                ),
                'status' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: [
                        $article->getStatusIconPinned(),
                        $article->getStatusIconDraft(),
                        $article->getStatusIconPostponed(),
                        $article->getStatusIconApproval(),
                        $article->getStatusIconComments()
                    ],
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_ICONS,
                    size: 'auto',
                    class: 'fpcm-ui-metabox'
                )
            ];

        }

        return $rows;

    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return 'RECENT_ARTICLES';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 2;
    }

    /**
     * Returns container width
     * @return int
     */
    public function getWidth()
    {
        return 8;
    }

    /**
     * Return button object
     * @return \fpcm\view\helper\linkButton|null
     * @since 5.0.0-b3
     */
    public function getButton(): ?\fpcm\view\helper\linkButton
    {
        return (new \fpcm\view\helper\linkButton('toActiveArticles'))
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('articles/listactive'))
                ->setIcon('newspaper', 'far')
                ->setText('HL_ARTICLE_EDIT');
    }

}
