<div class="fpcm-ui-editor-metabox-right">
    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $article->getPinned(); ?>" title="<?php $FPCM_LANG->write('EDITOR_STATUS_PINNED'); ?>">
        <span class="fa fa-square fa-stack-2x"></span>
        <span class="fa fa-thumb-tack fa-stack-1x fa-rotate-90 fa-inverse"></span>
    </span>    
    
    <?php if ($showDraftStatus) : ?>
    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $article->getDraft(); ?>" title="<?php $FPCM_LANG->write('EDITOR_STATUS_DRAFT'); ?>">
        <span class="fa fa-square fa-stack-2x"></span>
        <span class="fa fa-file-text-o fa-stack-1x fa-inverse"></span>
    </span>
    <?php endif; ?>

    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $article->getPostponed(); ?>" title="<?php $FPCM_LANG->write('EDITOR_STATUS_POSTPONETO'); ?><?php if ($article->getPostponed()) : ?>: <?php fpcm\model\view\helper::dateText($article->getCreatetime()); ?><?php endif; ?>">
        <span class="fa fa-square fa-stack-2x"></span>
        <span class="fa fa-clock-o fa-stack-1x fa-inverse"></span>
    </span>
    
    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $article->getApproval(); ?>" title="<?php $FPCM_LANG->write('EDITOR_STATUS_APPROVAL'); ?>">
        <span class="fa fa-square fa-stack-2x"></span>
        <span class="fa fa-thumbs-o-up fa-stack-1x fa-inverse"></span>
    </span>

    <?php if ($commentEnabledGlobal) : ?>
    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $article->getComments(); ?>" title="<?php $FPCM_LANG->write('EDITOR_STATUS_COMMENTS'); ?>">
        <span class="fa fa-square fa-stack-2x"></span>
        <span class="fa fa-comments-o fa-stack-1x fa-inverse"></span>
    </span>
    <?php endif; ?>
    
    <?php if ($showArchiveStatus) : ?>
    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $article->getArchived(); ?>" title="<?php $FPCM_LANG->write('EDITOR_STATUS_ARCHIVE'); ?>">
        <span class="fa fa-square fa-stack-2x"></span>
        <span class="fa fa-archive fa-stack-1x fa-inverse"></span>
    </span>
    <?php endif; ?>
</div>