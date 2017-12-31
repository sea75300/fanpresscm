<div class="fpcm-ui-editor-metabox-right">
    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $comment->getSpammer(); ?>" title="<?php $FPCM_LANG->write('COMMMENT_SPAM'); ?>">
        <span class="fa fa-square fa-stack-2x"></span>        
        <span class="fa fa-flag fa-stack-1x fa-inverse"></span>
    </span>
    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $comment->getApproved(); ?>" title="<?php $FPCM_LANG->write('COMMMENT_APPROVE'); ?>">
        <span class="fa fa-square fa-stack-2x"></span>
        <span class="fa fa-check-circle-o fa-stack-1x fa-inverse"></span>
    </span>
    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $comment->getPrivate(); ?>" title="<?php $FPCM_LANG->write('COMMMENT_PRIVATE'); ?>">
        <span class="fa fa-square fa-stack-2x"></span>
        <span class="fa fa-eye-slash fa-stack-1x fa-inverse"></span>
    </span>
</div>