<p><?php print $maxFilesInfo; ?></p>

<div class="fpcm-ui-controlgroup">
    <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus'); ?>
    <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('cloud-upload'); ?>
    <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban'); ?>
    <input type="file" name="files[]" multiple class="fpcm-ui-fileinput-select fpcm-ui-hidden">
</div>

<table id="fpcm-ui-phpupload-filelist" class="fpcm-ui-table fpcm-ui-phpupload"></table>