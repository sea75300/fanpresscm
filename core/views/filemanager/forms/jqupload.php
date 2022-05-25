<?php /* @var $theView fpcm\view\viewVars */ ?>
<div id="fileupload" class="fileupload-processing">

    <div class="row row-cols-1 row-cols-lg-2">
        <div class="col align-self-center mb-2">
            <div class="fileupload-buttonbar">
                <a class="btn btn-primary fpcm-ui-button fpcm-ui-button-link fileinput-button w-100 mb-2">
                    <?php $theView->icon('plus'); ?>
                    <span><?php $theView->write('FILE_FORM_FILEADD'); ?></span>
                    <input type="file" name="files[]" <?php if ($uploadMultiple) : ?>multiple<?php endif; ?>>
                </a>
                <?php $theView->submitButton('start')->setText('FILE_FORM_UPLOADSTART')->setClass('start w-100 mb-2')->setIcon('upload')->overrideButtonType('outline-secondary'); ?>
                <?php $theView->resetButton('cancel')->setText('FILE_FORM_UPLOADCANCEL')->setClass('cancel w-100')->setIcon('ban')->overrideButtonType('outline-secondary'); ?>
            </div>
        </div>
        <div class="col align-self-center">
            <div id="fpcm-filemanager-upload-drop" class="col-12 bg-light shadow d-none d-lg-block">
                <h4 class="text-center d-block"><?php $theView->icon('file-upload')->setSize('2x')->setClass('opacity-75'); ?><br><span class="fs-6"><?php $theView->write('FILE_LIST_UPLOADDROP'); ?></span></h4>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col">
            <div class="fileupload-progress fade">
                <div class="fpcm-ui-progressbar progress active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="ui-progressbar-value progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <div class="progress-extended fpcm ui-progressbar-label">&nbsp;</div>
            </div>
        </div>
    </div>

    <div  class="row my-2" role="presentation">
        <div class="col-12 px-0 files"></div>
    </div>
</div>