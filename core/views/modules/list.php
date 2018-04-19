<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-modules-list"><?php $theView->write('MODULES_LIST_HEADLINE'); ?></a></li>
            <?php if ($canInstall) : ?><li><a href="#tabs-modules-upload"><?php $theView->write('MODULES_LIST_UPLOAD'); ?></a></li><?php endif; ?>
        </ul>

        <div id="tabs-modules-list">
            <div id="fpcm-dataview-modulelist"></div>       
        </div>
        
        <?php if ($canInstall) : ?>
        <div id="tabs-modules-upload">
            <?php include $theView->getIncludePath('filemanager/forms/phpupload.php'); ?>
        </div>
        <?php endif; ?>
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