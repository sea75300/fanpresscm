<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\dashboard\containers;

/**
 * Recent comments dashboard container object
 *
 * @package fpcm\model\dashboard
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class recentcomments extends \fpcm\model\dashboard\types\dataview implements \fpcm\model\interfaces\isAccessible {

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
        return $this->config->system_comments_enabled && $this->permissions->editComments();
    }

    /**
     * Returns container name
     * @return string
     */
    public function getName()
    {
        return 'recentcomments';
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
        $search = new \fpcm\model\comments\search();
        $search->searchtype = 0;
        $search->deleted = 0;
        $search->limit = array(10, 0);
        $search->orderby = array('createtime DESC');

        $comments = (new \fpcm\model\comments\commentList())->getCommentsBySearchCondition($search);
        if (!count($comments)) {
            return [];
        }

        $rows = [];

        $nochangeStr = $this->language->translate('GLOBAL_NOCHANGE');
        
        /* @var $comment \fpcm\model\articles\article */
        foreach ($comments as $comment) {

            $chgstr = $this->language->translate('GLOBAL_USER_ON_TIME', array(
                '{{username}}' => \fpcm\classes\tools::userId2Text($comment->getChangeuser()),
                '{{time}}' => date($this->config->system_dtmask, $comment->getChangetime())
            ));

            if (!$comment->getChangeuser() && !$comment->getChangetime()) {
                $chgstr = $nochangeStr;
            }

            $headline = sprintf(
                '<strong>%s @ %s</strong><br><span class="text-secondary">%s</span>',
                new \fpcm\view\helper\escape(strip_tags($comment->getName())),
                new \fpcm\view\helper\dateText($comment->getCreatetime()),
                $chgstr
            );

            $rows[] = [
                'open' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: (new \fpcm\view\helper\openButton('openBtn'))->setUrlbyObject($comment)->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW),
                    type: \fpcm\model\dashboard\components\dataviewItem::TYPE_LINK,
                    class: 'list-group-item-primary'
                ),
                'edit' => new \fpcm\model\dashboard\components\dataviewItem(
                    value: (new \fpcm\view\helper\editButton('editBtn'))->setUrlbyObject($comment)->setReadonly(!$comment->getEditPermission()),
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
                        $comment->getStatusIconSpam(),
                        $comment->getStatusIconApproved(),
                        $comment->getStatusIconPrivate()
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
        return 'RECENT_COMMENTS';
    }

    /**
     * Returns container position
     * @return int
     */
    public function getPosition()
    {
        return 4;
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
     * Content rendern
     */
    private function renderContent()
    {

    }

    /**
     * Return button object
     * @return \fpcm\view\helper\linkButton|null
     * @since 5.0.0-b3
     */
    public function getButton(): ?\fpcm\view\helper\linkButton
    {
        return (new \fpcm\view\helper\linkButton('toCommentList'))
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('comments/list'))
                ->setIcon('comments', 'far')
                ->setText('HL_COMMENTS_MNG');
    }

}
