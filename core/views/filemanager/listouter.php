<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if($mode > 1) : ?><?php include_once $theView->getIncludePath('common/buttons.php'); ?><?php endif; ?>
<div class="<?php if($mode > 1) : ?>fpcm-ui-inner-wrapper<?php else : ?>fpcm-content-wrapper<?php endif; ?>">
    <div class="fpcm-ui-tabs-general ui-tabs ui-corner-all ui-widget ui-widget-content <?php if($mode > 1) : ?>fpcm-ui-full-view-min-height<?php endif; ?>" id="fpcm-files-tabs">
        <ul class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
            <?php $theView->tabItem('tabs-files-list-reload', 'tabs-files-list-reload')
                    ->setText('FILE_LIST_AVAILABLE')
                    ->setData(['toolbar-buttons' => 1])
                    ->setUrl('#tabs-files-list'); ?>            
        
            <?php if ($theView->permissions->uploads->add) : ?>
                <?php $theView->tabItem('tabs-files-list-reload', 'tabs-files-list-reload')
                        ->setText('FILE_LIST_UPLOADFORM')
                        ->setData(['toolbar-buttons' => 2])
                        ->setUrl('#tabs-files-upload'); ?>
            <?php endif; ?>                
        </ul>

        <div id="tabs-files-list">
            <div id="tabs-files-list-content">
                <?php if (!$hasFiles) : ?>
                <p class="fpcm-ui-padding-none fpcm-ui-margin-none"><?php $theView->icon('images', 'far')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
                <?php else : ?>
                <div class="row g-0 align-self-center fpcm-ui-inline-loader">
                    <div class="col-12 fpcm-ui-center align-self-center">
                        <?php $theView->icon('spinner fa-inverse')->setSpinner('pulse')->setStack('circle')->setSize('2x'); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($theView->permissions->uploads->add) : ?>
        <div id="tabs-files-upload">
            <?php include $uploadTemplatePath; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-files-rename">
    <div class="row py-2">
        <?php $theView->textInput('newFilenameDialog')
            ->setText('FILE_LIST_FILENAME')
            ->setValue('')
            ->setIcon('edit'); ?>
    </div>    
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-files-alttext">
    <div class="row py-2">
        <?php $theView->textInput('altTextDialog')
            ->setText('FILE_LIST_ALTTEXT')
            ->setValue('')
            ->setIcon('edit'); ?>
    </div>    
</div>

<?php include $theView->getIncludePath('filemanager/searchform.php'); ?>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-files-properties">

    <div class="row my-2">
        <label class="col-12 col-md-4 fpcm-ui-field-label-general">
            <?php $theView->icon('calendar-alt', 'far')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_UPLOAD_DATE'); ?>
        </label>
        <div class="col-sm-12 col-md-8 align-self-center fpcm-ui-border-grey-medium p-2 fpcm-ui-border-radius-all" id="fpcm-dialog-files-properties-filetime"></div>
    </div>

    <div class="row my-2">
        <label class="col-12 col-md-4 fpcm-ui-field-label-general">
            <?php $theView->icon('user')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_UPLOAD_BY'); ?>
        </label>
        <div class="col-sm-12 col-md-8 align-self-center fpcm-ui-border-grey-medium p-2 fpcm-ui-border-radius-all" id="fpcm-dialog-files-properties-fileuser"></div>
    </div>

    <div class="row my-2">
        <label class="col-12 col-md-4 fpcm-ui-field-label-general">
            <?php $theView->icon('weight')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILESIZE'); ?>
        </label>
        <div class="col-sm-12 col-md-8 align-self-center fpcm-ui-border-grey-medium p-2 fpcm-ui-border-radius-all" id="fpcm-dialog-files-properties-filesize"></div>
    </div>

    <div class="row my-2">
        <label class="col-12 col-md-4 fpcm-ui-field-label-general">
            <?php $theView->icon('expand-arrows-alt')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_RESOLUTION'); ?>
        </label>
        <div class="col-sm-12 col-md-8 align-self-center fpcm-ui-border-grey-medium p-2 fpcm-ui-border-radius-all" id="fpcm-dialog-files-properties-resulution"></div>
    </div>

    <div class="row my-2">
        <label class="col-12 col-md-4 fpcm-ui-field-label-general">
            <?php $theView->icon('file-alt ')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILETYPE'); ?>
        </label>
        <div class="col-sm-12 col-md-8 align-self-center fpcm-ui-border-grey-medium p-2 fpcm-ui-border-radius-all" id="fpcm-dialog-files-properties-filemime"></div>
    </div>

    <div class="row my-2">
        <label class="col-12 col-md-4 fpcm-ui-field-label-general">
            <?php $theView->icon('hashtag')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILEHASH'); ?>
        </label>
        <div class="col-sm-12 col-md-8 align-self-center fpcm-ui-border-grey-medium fpcm-ui-ellipsis p-2 fpcm-ui-border-radius-all fpcm-ui-ellipsis" id="fpcm-dialog-files-properties-filehash"></div>
    </div>

    <div class="row my-2">
        <label class="col-12 col-md-4 fpcm-ui-field-label-general">
            <?php $theView->icon('copyright')->setSize('lg'); ?>
            <?php $theView->write('FILE_LIST_FILECREDITS'); ?>
        </label>
        <div class="col-sm-12 col-md-8 align-self-center fpcm-ui-border-grey-medium fpcm-ui-ellipsis p-2 pre-box fpcm-ui-border-radius-all" id="fpcm-dialog-files-properties-credits">
            &nbsp;
        </div>
    </div>

</div>