<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog fpcm-massedit-dialog" id="fpcm-dialog-articles-massedit">
    
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-tags fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('TEMPLATE_ARTICLE_CATEGORYTEXTS'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col">
            <div class="fpcm-ui-massedit-categories">
                <div class="fpcm-ui-buttonset">
                    <?php foreach ($massEditCategories as $name => $id) : ?>
                        <?php fpcm\model\view\helper::checkbox('categories[]', 'fpcm-ui-input-massedit-categories', $id, $name, 'cat'.$id, false); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="fpcm-clear"></div>
    </div>

    <?php if ($canChangeAuthor) : ?>
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-user fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('EDITOR_CHANGEAUTHOR'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col"><?php \fpcm\model\view\helper::select('userid', $massEditUsers, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'); ?></div>
        <div class="fpcm-clear"></div>
    </div>
    <?php endif; ?>
    
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-thumb-tack fa-rotate-90 fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('EDITOR_PINNED'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col"><?php \fpcm\model\view\helper::select('pinned', $massEditPinned, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'); ?></div>
        <div class="fpcm-clear"></div>
    </div>
    
    <?php if ($showDraftStatus) : ?>
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-file-text-o fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('EDITOR_DRAFT'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col"><?php \fpcm\model\view\helper::select('draft', $massEditDraft, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'); ?></div>
        <div class="fpcm-clear"></div>
    </div>
    <?php endif; ?>

    <?php if (!$canApprove) : ?>
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-thumbs-o-up fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('EDITOR_STATUS_APPROVAL'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col"><?php \fpcm\model\view\helper::select('approval', $massEditApproved, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'); ?></div>
        <div class="fpcm-clear"></div>
    </div>
    <?php endif; ?>
    
    <?php if ($commentEnabledGlobal) : ?>
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-comments-o fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('EDITOR_COMMENTS'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col"><?php \fpcm\model\view\helper::select('comments', $massEditComments, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'); ?></div>
        <div class="fpcm-clear"></div>
    </div>
    <?php endif; ?>
    
    <?php if ($canArchive) : ?>
    <div class="fpcm-ui-editor-extended-row">
        <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-archive fa-fw fa-lg"></span></div>
        <div class="fpcm-ui-editor-extended-button"><label><?php $FPCM_LANG->write('EDITOR_ARCHIVE'); ?></label></div>
        <div class="fpcm-ui-editor-extended-col"><?php \fpcm\model\view\helper::select('archived', $massEditArchived, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'); ?></div>
        <div class="fpcm-clear"></div>
    </div>
    <?php endif; ?>
</div>