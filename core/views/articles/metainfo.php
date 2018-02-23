<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-editor-metabox-right fpcm-ui-metabox">
    <?php $theView->icon('thumb-tack fa-rotate-90 fa-inverse')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getPinned())->setText('EDITOR_STATUS_PINNED')->setStack('square'); ?>
    <?php if ($showDraftStatus) : ?><?php $theView->icon('file-text-o fa-inverse')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getDraft())->setText('EDITOR_STATUS_DRAFT')->setStack('square'); ?><?php endif; ?>
    <?php $desc = $theView->translate('EDITOR_STATUS_POSTPONETO').($article->getPostponed() ? ' '.$theView->dateText($article->getCreatetime()) : ''); ?>
    <?php $theView->icon('clock-o fa-inverse')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getPostponed())->setText($desc)->setStack('square'); ?>   
    <?php $theView->icon('thumbs-o-up fa-inverse')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getApproval())->setText('EDITOR_STATUS_APPROVAL')->setStack('square'); ?>
    <?php if ($commentEnabledGlobal) : ?><?php $theView->icon('comments-o fa-inverse')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getComments())->setText('EDITOR_STATUS_COMMENTS')->setStack('square'); ?><?php endif; ?>
    <?php if ($showArchiveStatus) : ?><?php $theView->icon('archive fa-inverse')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getArchived())->setText('EDITOR_STATUS_ARCHIVE')->setStack('square'); ?><?php endif; ?>
</div>