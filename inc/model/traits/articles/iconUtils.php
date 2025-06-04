<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits\articles;

/**
 * Article icons utils
 * 
 * @package fpcm\model\traits\articles
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.0-a1
 */
trait iconUtils {

    /**
     * Returns pinned status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconPinned()
    {
        return $this->getStatusColor((new \fpcm\view\helper\icon('thumbtack fa-rotate-90 fa-inverse'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-pinned')
                        ->setText('EDITOR_STATUS_PINNED')
                        ->setStack('square'), $this->getPinned());
    }

    /**
     * Returns draft status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconDraft()
    {
        return $this->getStatusColor((new \fpcm\view\helper\icon('file-alt fa-inverse', 'far'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-draft')
                        ->setText('EDITOR_STATUS_DRAFT')
                        ->setStack('square'), $this->getDraft());
    }

    /**
     * Returns postponed status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconPostponed()
    {
        return $this->getStatusColor((new \fpcm\view\helper\icon('calendar-plus fa-inverse'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-postponed')
                        ->setText($this->language->translate('EDITOR_STATUS_POSTPONETO') . ( $this->getPostponed() ? ' ' . new \fpcm\view\helper\dateText($this->getCreatetime()) : ''))
                        ->setStack('square'), $this->getPostponed());
    }

    /**
     * Returns approval status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconApproval()
    {
        return $this->getStatusColor((new \fpcm\view\helper\icon('thumbs-up fa-inverse', 'far'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-approval')
                        ->setText('EDITOR_STATUS_APPROVAL')
                        ->setStack('square'), $this->getApproval());
    }

    /**
     * Returns comments enabled status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconComments()
    {
        return $this->getStatusColor((new \fpcm\view\helper\icon('comments fa-inverse', 'far'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-comments')
                        ->setText('EDITOR_STATUS_COMMENTS')
                        ->setStack('square'), $this->getComments());
    }

    /**
     * Returns archive status icon
     * @return \fpcm\view\helper\icon
     */
    public function getStatusIconArchive()
    {
        return $this->getStatusColor((new \fpcm\view\helper\icon('archive fa-inverse'))
                        ->setClass('fpcm-ui-editor-metainfo fpcm-ui-editor-metainfo-archived')
                        ->setText('EDITOR_STATUS_ARCHIVE')
                        ->setStack('square'), $this->getArchived());
    }

    /**
     * Returns array with all status icons
     * @param bool $showDraftStatus
     * @param bool $showCommentsStatus
     * @param bool $showArchivedStatus
     * @return array
     */
    public function getMetaDataStatusIcons($showDraftStatus, $showCommentsStatus, $showArchivedStatus)
    {
        return [
            $this->getStatusIconPinned(),
            $showDraftStatus ? $this->getStatusIconDraft() : '',
            $showCommentsStatus ? $this->getStatusIconComments() : '',
            $this->getStatusIconApproval(),
            $showArchivedStatus ? $this->getStatusIconArchive() : '',
            $this->getStatusIconPostponed(),
        ];
    }

}
