<?php if ($permRename || $permThumbs || $permDelete) : ?>
<div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons fpcm-filemanager-buttons <?php if ($mode > 1) : ?>fpcm-ui-hidden fpcm-buttons-fixed-full<?php endif; ?>">
    <div class="fpcm-ui-margin-center">
    <?php fpcm\view\helper::checkbox('fpcm-select-all', 'fpcm-select-all-checkbutton', '', '', 'fpcm-select-all', false); ?>
    <?php \fpcm\view\helper::linkButton('#', 'ARTICLES_SEARCH', 'fpcmfilessopensearch', 'fpcm-articles-opensearch'); ?>
    <?php if ($permRename) : ?>
        <?php fpcm\view\helper::submitButton('renameFiles', 'FILE_LIST_RENAME', 'fpcm-loader fpcm-rename-btn'); ?> 
        <input type="hidden" name="newfilename" id="newfilename" value="">
    <?php endif; ?>
    <?php if ($permThumbs) : ?>
        <?php fpcm\view\helper::submitButton('createThumbs', 'FILE_LIST_NEWTHUMBS', 'fpcm-loader fpcm-newthumb-btn'); ?>
    <?php endif; ?>
    <?php if ($permDelete) : ?>
        <?php fpcm\view\helper::deleteButton('deleteFiles'); ?>
    <?php endif; ?>
    </div>  
</div>
<?php endif; ?>