<form action="<?php print $actionPath; ?>" method="POST" enctype="multipart/form-data">
    <p><?php print $maxFilesInfo; ?></p>
    
    <table id="fpcm-ui-phpupload-filelist" class="fpcm-ui-table fpcm-ui-phpupload"></table>
    
    <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> <?php if (!$styleLeftMargin) : ?>fpcm-buttons-fixed-full<?php endif; ?> fpcm-filemanager-buttons">
        <span class="fpcm-ui-fileinput-php">
        <?php fpcm\view\helper::linkButton('#', 'FILE_FORM_FILEADD', 'btnAddFile') ?>
        <?php fpcm\view\helper::submitButton('uploadFile', 'FILE_FORM_UPLOADSTART', 'start-upload fpcm-loader'); ?>

        <button type="reset" class="cancel-upload" id="btnCancelUpload"><?php $theView->lang->write('FILE_FORM_UPLOADCANCEL'); ?></button>

            <input type="file" name="files[]" multiple class="fpcm-ui-fileinput-select fpcm-ui-hidden">
        </span>
    </div>
</form>