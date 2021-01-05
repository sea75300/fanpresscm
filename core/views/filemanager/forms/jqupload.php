<?php /* @var $theView fpcm\view\viewVars */ ?>
<div id="fileupload" class="fileupload-processing">
    <div class="row no-gutters">
        <div class="col-12">
            <div class="fileupload-progress fpcm-ui-fade fpcm-ui-hidden my-3">
                <div class="fpcm ui-progressbar progress active ui-progressbar ui-corner-all ui-widget ui-widget-content" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-extended fpcm ui-progressbar-label">&nbsp;</div>
                    <div class="ui-progressbar-value ui-corner-left ui-widget-header progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
            </div>            
        </div>
    </div>

    <div class="row no-gutters">
        
        <div class="col-12 mb-1">
            <fieldset class="fpcm-ui-margin-lg-bottom">
                <legend><?php $theView->write('FILE_LIST_UPLOADFORM'); ?></legend>

                <div class="fpcm-ui-padding-md-tb fileupload-buttonbar">
                    <div class="fileupload-buttons fpcm-ui-controlgroup">
                        <a class="fileinput-button">
                            <?php $theView->icon('plus'); ?>
                            <span><?php $theView->write('FILE_FORM_FILEADD'); ?></span>
                            <input type="file" name="files[]" <?php if ($uploadMultiple) : ?>multiple<?php endif; ?>>
                        </a>

                        <?php $theView->submitButton('start')->setText('FILE_FORM_UPLOADSTART')->setClass('start')->setIcon('upload'); ?>
                        <?php $theView->resetButton('cancel')->setText('FILE_FORM_UPLOADCANCEL')->setClass('cancel')->setIcon('ban'); ?>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="col-12 my-1">
            <div class="row no-gutters align-self-center justify-content-center">        
                <div id="fpcm-filemanager-upload-drop" class="col-12 fpcm-ui-background-white-100">
                    <h4 class="fpcm-ui-center"><?php $theView->icon('file-upload')->setSize('4x')->setClass('fpcm-ui-padding-md-bottom fpcm ui-status-075'); ?><br><?php $theView->write('FILE_LIST_UPLOADDROP'); ?></h4>
                </div>
            </div>
        </div>
        
    </div>


    <div role="presentation" class="fpcm-ui-margin-lg-top">
        <div class="files"></div>
    </div>
</div>