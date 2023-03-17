<?php /* @var $theView fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('FILE_LIST_UPLOADFORM'); ?>: <?php print $maxFilesInfo; ?></legend>

    <div class="rounded-2 p-2 bg-white shadow-sm">
        <div class="btn-group-vertical back w-100" role="group">
            <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus')->setData(['click-trigger' => 'upload-files-select']); ?>
            <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('upload'); ?>
            <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban'); ?>
        </div>    
        <input type="file" name="files[]" multiple class="fpcm-ui-fileinput-select invisible" id="fpcm-id-upload-files-select">
    </div>
</fieldset>

<fieldset class="mt-2">
    <legend><?php $theView->write('FILE_LIST_UPLOADSELECTED'); ?></legend>
    <div class="list-group my-2" id="fpcm-id-upload-list" role="presentation"></div>
</fieldset>
