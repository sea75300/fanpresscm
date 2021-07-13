<?php /* @var $theView fpcm\view\viewVars */ ?>
<div id="fileupload" class="fileupload-processing">
    <div class="row">
        <div class="col-12">
            <div class="fileupload-progress fpcm-ui-fade fpcm-ui-hidden my-3">
                <div class="fpcm ui-progressbar progress active ui-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="ui-progressbar-value progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
            </div>            
            <div class="progress-extended fpcm ui-progressbar-label m-1 px-2">&nbsp;</div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-12 my-3">
            <div class="fileupload-buttonbar">
                <div class="fileupload-buttons fpcm-ui-controlgroup">
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

        <div class="col-12 mb-3">
            <div class="row align-self-center justify-content-center">        
                <div id="fpcm-filemanager-upload-drop" class="col-12 fpcm-ui-background-white-100">
                    <h4 class="fpcm-ui-center"><?php $theView->icon('file-upload')->setSize('4x')->setClass('fpcm ui-status-075'); ?><br><?php $theView->write('FILE_LIST_UPLOADDROP'); ?></h4>
                </div>
            </div>
        </div>
        
    </div>

    <div  class="row" role="presentation">
        <div class="col files"></div>
    </div>
</div>