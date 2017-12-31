<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog fpcm-massedit-dialog" id="fpcm-dialog-comments-massedit">

    <?php if ($canApprove) : ?>
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-flag fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('COMMMENT_SPAM'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col"><?php \fpcm\model\view\helper::select('isSpam', $massEditSpam, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'); ?></div>
        <div class="fpcm-clear"></div>
    </div>
    
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-check-circle-o fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('COMMMENT_APPROVE'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col"><?php \fpcm\model\view\helper::select('isApproved', $massEditApproved, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'); ?></div>
        <div class="fpcm-clear"></div>
    </div>
    <?php endif; ?>
    
    <?php if ($canPrivate) : ?>
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-eye-slash fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('COMMMENT_PRIVATE'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col"><?php \fpcm\model\view\helper::select('isPrivate', $massEditPrivate, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'); ?></div>
        <div class="fpcm-clear"></div>
    </div>
    <?php endif; ?>
    <?php if ($commentsMode === 1 && $canMove) : ?>    
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-clipboard fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('COMMMENT_MOVE'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col"><?php \fpcm\model\view\helper::textInput('moveToArticle', 'fpcm-ui-input-massedit', '', false, 20); ?></div>
        <div class="fpcm-clear"></div>
    </div>
    <?php endif; ?>
</div>