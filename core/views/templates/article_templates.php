<?php /* @var $theView fpcm\view\viewVars */ ?>
<p><?php print $maxFilesInfo; ?></p>

<div class="fpcm-ui-controlgroup fpcm-ui-margin-lg-bottom" id="article_template_buttons">    
    <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus'); ?>
    <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('upload'); ?>
    <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban'); ?>
    <input type="file" name="files[]" class="fpcm-ui-fileinput-select fpcm-ui-hidden">
</div>

<div id="fpcm-ui-phpupload-filelist" class="fpcm-ui-margin-lg-bottom fpcm-ui-filelist fpcm-ui-phpupload"></div>

<div id="fpcm-dataview-draftfiles"></div>