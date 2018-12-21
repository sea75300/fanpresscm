<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if($mode > 1) : ?><?php include_once $theView->getIncludePath('common/buttons.php'); ?><?php endif; ?>
<div class="fpcm-ui-inner-wrapper fpcm-ui-full-height">
    <div class="fpcm-ui-tabs-general" id="fpcm-files-tabs">
        <ul>
            <li data-toolbar-buttons="1" id="tabs-files-list-reload"><a href="#tabs-files-list"><?php $theView->write('FILE_LIST_AVAILABLE'); ?></a></li>                
            <?php if ($permUpload) : ?><li data-toolbar-buttons="2"><a href="#tabs-files-upload"><?php $theView->write('FILE_LIST_UPLOADFORM'); ?></a></li><?php endif; ?>                
        </ul>

        <div id="tabs-files-list">
            <div id="tabs-files-list-content">
                <?php if (!$hasFiles) : ?>
                <p class="fpcm-ui-padding-none fpcm-ui-margin-none"><?php $theView->icon('images', 'far')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($permUpload) : ?>
        <?php if ($newUploader) : ?></form><?php endif; ?>
        <div id="tabs-files-upload">
            <?php if ($newUploader) : ?>
                <?php include $theView->getIncludePath('filemanager/forms/jqupload.php'); ?>
            <?php else : ?>
                <?php include $theView->getIncludePath('filemanager/forms/phpupload.php'); ?>
            <?php endif; ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-files-rename">
    <div class="row">
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('FILE_LIST_FILENAME'); ?>:</div>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb"><?php $theView->textInput('newFilenameDialog'); ?></div>
    </div>
</div>

<?php include $theView->getIncludePath('filemanager/searchform.php'); ?>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-files-properties">
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-1">
            <?php $theView->icon('calendar-alt', 'far')->setText('FILE_LIST_UPLOAD_DATE')->setSize('lg'); ?>
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-dialog-files-properties-filetime"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-1">
            <?php $theView->icon('user')->setText('FILE_LIST_UPLOAD_BY')->setSize('lg'); ?>
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-dialog-files-properties-fileuser"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-1">
            <?php $theView->icon('weight')->setText('FILE_LIST_FILESIZE')->setSize('lg'); ?>
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-dialog-files-properties-filesize"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-1">
            <?php $theView->icon('expand-arrows-alt')->setText('FILE_LIST_RESOLUTION')->setSize('lg'); ?>
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-dialog-files-properties-resulution"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-1">
            <?php $theView->icon('file-alt ')->setText('FILE_LIST_FILETYPE')->setSize('lg'); ?>
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-dialog-files-properties-filemime"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-1">
            <?php $theView->icon('hashtag')->setText('FILE_LIST_FILEHASH')->setSize('lg'); ?>
        </div>
        <div class="col-sm-12 col-md-8" id="fpcm-dialog-files-properties-filehash"></div>
    </div>
</div>