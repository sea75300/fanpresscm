<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="border-top border-5 border-primary">
    <div id="fileupload" class="fileupload-processing">
        <div class="row">
            <div class="col-12">
                <div class="fileupload-progress fpcm-ui-fade fpcm-ui-hidden my-3">
                    <div class="fpcm-ui-progressbar progress active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="ui-progressbar-value progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                    <div class="progress-extended fpcm ui-progressbar-label">&nbsp;</div>
                </div>            
            </div>
        </div>

        <div class="row justify-content-center my-2">
            <div class="col-12 col-md-9 col-md-6">
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
        </div>

        <div class="row justify-content-center my-2">
            <div class="col-12 col-md-9 col-md-6">
                <div class="row align-self-center">        
                    <div id="fpcm-filemanager-upload-drop" class="col-12 bg-light">
                        <h4 class="fpcm-ui-center"><?php $theView->icon('file-upload')->setSize('4x')->setClass('fpcm ui-status-075'); ?><br><?php $theView->write('FILE_LIST_UPLOADDROP'); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div  class="row" role="presentation">
            <div class="col files"></div>
        </div>
    </div>
</div>