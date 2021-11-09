<?php /* @var $theView fpcm\view\viewVars */ ?>
<div id="fileupload" class="fileupload-processing">

    <div class="row mb-2 gap-1">
        <div class="col-12 col-sm-auto">
            <div class="fileupload-buttonbar">
                <a class="btn btn-primary fpcm-ui-button fpcm-ui-button-link fileinput-button">
                    <?php $theView->icon('plus'); ?>
                    <span><?php $theView->write('FILE_FORM_FILEADD'); ?></span>
                    <input type="file" name="files[]" <?php if ($uploadMultiple) : ?>multiple<?php endif; ?>>
                </a>
                <?php $theView->submitButton('start')->setText('FILE_FORM_UPLOADSTART')->setClass('start')->setIcon('upload'); ?>
                <?php $theView->resetButton('cancel')->setText('FILE_FORM_UPLOADCANCEL')->setClass('cancel')->setIcon('ban'); ?>
            </div>
        </div>
        <div class="col-12 col-sm-auto flex-grow-1 align-self-center pe-0">
            <div class="fileupload-progress fade">
                <div class="fpcm-ui-progressbar progress active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="ui-progressbar-value progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <div class="progress-extended fpcm ui-progressbar-label">&nbsp;</div>
            </div>
        </div>
    </div>

    <div class="row align-self-center">        
        <div id="fpcm-filemanager-upload-drop" class="col-12 bg-light">
            <h4 class="text-center d-block"><?php $theView->icon('file-upload')->setSize('4x')->setClass('opacity-75'); ?><br><?php $theView->write('FILE_LIST_UPLOADDROP'); ?></h4>
        </div>
    </div>

    <div  class="row my-2" role="presentation">
        <div class="col-12 px-0 files"></div>
    </div>
</div>