<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-full-height">
    <div class="col-12">
        <div class="fpcm-content-wrapper fpcm-ui-full-height">
            <div class="fpcm-ui-tabs-general" id="fpcm-tabs-modules">
                <ul>
                    <li data-dataview-list="modulesLocal"><a href="<?php print fpcm\classes\tools::getFullControllerLink('ajax/modules/fetch', ['mode' => 'local']); ?>"><?php $theView->write('MODULES_LIST_HEADLINE'); ?></a></li>
                    <li data-dataview-list="modulesRemote"><a href="<?php print fpcm\classes\tools::getFullControllerLink('ajax/modules/fetch', ['mode' => 'remote']); ?>"><?php $theView->write('MODULES_LIST_AVAILABLE'); ?></a></li>
                    <?php if ($canInstall) : ?><li><a href="#tabs-modules-upload"><?php $theView->write('MODULES_LIST_UPLOAD'); ?></a></li><?php endif; ?>
                </ul>

                <?php if ($canInstall) : ?>
                <div id="tabs-modules-upload">
                    <p><?php print $maxFilesInfo; ?></p>

                    <div class="fpcm-ui-controlgroup fpcm-ui-margin-lg-bottom" id="article_template_buttons">    
                        <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus'); ?>
                        <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('upload'); ?>
                        <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban'); ?>
                        <input type="file" name="files[]" class="fpcm-ui-fileinput-select fpcm-ui-hidden">
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden" id="fpcm-dialog-modulelist-infos">
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->write('MODULES_LIST_NAME'); ?>:
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-modulelist-info-name"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->write('MODULES_LIST_AUTHOR'); ?>:
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-modulelist-info-author"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->write('MODULES_LIST_LINK'); ?>:
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-modulelist-info-link"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->write('MODULES_LIST_REQUIRE_FPCM'); ?>:
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-modulelist-info-require-system"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->write('MODULES_LIST_REQUIRE_PHP'); ?>:
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-modulelist-info-require-php"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-4">
            <?php $theView->write('MODULES_LIST_DESCRIPTION'); ?>:
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-modulelist-info-description"></div>
    </div>
</div>