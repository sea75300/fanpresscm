<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_SETTINGS'); ?></legend>

    <div class="row g-0 my-2">
        <div class="col-12 col-md-8">
            <div class="row row-cols-1 row-cols-sm-2">
                <div class="col">
                        <?php $theView->select('system_editor')
                            ->setText('SYSTEM_OPTIONS_NEWS_EDITOR')
                            ->setOptions($editors)
                            ->setSelected(base64_encode($globalConfig->system_editor)); 
                        ?>
                </div>

                <div class="col">
                <?php $theView->select('system_editor_fontsize')
                    ->setText('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE')
                        ->setOptions($defaultFontsizes)
                        ->setSelected($globalConfig->system_editor_fontsize); ?>
                </div>
            </div>            
        </div>
    </div>

    <div class="row g-0 my-2">
        <div class="col-12 col-md-8">
            <div class="row row-cols-1 row-cols-sm-2">
                <div class="col">
                    <?php $theView->boolSelect('articles_revisions')
                            ->setText('SYSTEM_OPTIONS_REVISIONS_ENABLED')
                            ->setSelected($globalConfig->articles_revisions); ?>
                </div>

                <div class="col">
                    <?php $theView->select('articles_revisions_limit')
                            ->setText('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT')
                            ->setOptions($theView->translate('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT_LIST'))
                            ->setSelected($globalConfig->articles_revisions_limit); ?>
                </div>
            </div>            
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
            <?php $theView->textarea('system_editor_css')
                    ->setValue($globalConfig->system_editor_css, ENT_QUOTES)
                    ->setClass('fpcm ui-textarea-medium ui-textarea-noresize')
                    ->setText('SYSTEM_OPTIONS_NEWS_EDITOR_CSS')
                    ->setPlaceholder('.your_css_class{}'); ?>
        </div>
    </div>

</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('HL_FILES_MNG'); ?></legend>

    <div class="row g-0 my-2">
        <div class="col-12 col-md-8">
            <div class="row row-cols-1 row-cols-sm-2">
                <div class="col">
                <?php $theView->select('file_list_limit')
                    ->setText('SYSTEM_OPTIONS_FILEMANAGER_LIMIT')
                    ->setOptions($articleLimitListAcp)
                    ->setSelected($globalConfig->file_list_limit); ?>
                </div>

                <div class="col">
                <?php $theView->select('file_view')
                    ->setText('SYSTEM_OPTIONS_FILEMANAGER_VIEW')
                    ->setOptions($filemanagerViews)
                    ->setSelected($globalConfig->file_view); ?>
                </div>
            </div>            
        </div>
    </div>
    
    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->boolSelect('file_subfolders')
                ->setText('SYSTEM_OPTIONS_NEWS_SUBFOLDERS')
                ->setSelected($globalConfig->file_subfolders); ?>
        </div>
    </div>
    
    <div class="row my-2">
        <div class="col-12 col-md-8">
            <div class="row g-0">
                <div class="col flex-grow-1">
                    <?php $theView->textInput('file_cropper_name')->setValue($globalConfig->file_cropper_name); ?>
                </div>
                <div class="col-auto align-self-center mx-3 mb-3">
                    <?php $theView->shorthelpButton('cropper_name')->setText('LABEL_FIELD_FILE_CROPPER_NAME_HELP'); ?>                    
                </div>
            </div>        
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->rangeInput('file_thumb_size')
                ->setText('FILE_LIST_THUMB_SIZE')
                ->setClass('d-flex col-sm-6 col-md-4 mt-2')
                ->setValue($globalConfig->file_thumb_size)
                ->setStep(50)
                ->setMin(50)
                ->setMax(400); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
            <div class="row g-0">
                <div class="col-12 col-sm-6 col-md-4">&nbsp;</div>
                <div class="col-auto justify-content-center">
                    <figure class="figure" id="fpcm-thumb-preview">
                        <img title="<?php $theView->write('GLOBAL_PREVIEW'); ?>" class="img-thumbnail bg-light border border-2 border-info" src="<?php print $theView->themePath; ?>logo.svg" role="presentation" style="width:<?php print $globalConfig->file_thumb_size; ?>px;height: <?php print $globalConfig->file_thumb_size; ?>px;">
                        <figcaption class="figure-caption text-end"><span><?php print $theView->escapeVal($globalConfig->file_thumb_size); ?></span> <?php $theView->write('FILE_LIST_RESOLUTION_PIXEL') ?></figcaption>
                    </figure>
                </div>
            </div>
            
        </div>
        
        
    </div>
</fieldset>