<?php /* @var $theView fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('FILE_LIST_UPLOADFORM'); ?>: <?php print $maxFilesInfo; ?></legend>

    <div class="fpcm-ui-controlgroup py-2" id="article_template_buttons">    
        <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus'); ?>
        <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('upload'); ?>
        <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban'); ?>
        <input type="file" name="files[]" multiple class="fpcm-ui-fileinput-select fpcm-ui-hidden">
    </div>
</fieldset>

<fieldset class="mt-2 fpcm-ui-hidden" id="fpcm-ui-fileupload-list">
    <legend><?php $theView->write('FILE_LIST_UPLOADSELECTED'); ?></legend>
    <div id="fpcm-ui-phpupload-filelist" class="fpcm-ui-phpupload"></div>
</fieldset>
