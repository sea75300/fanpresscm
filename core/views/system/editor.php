<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_SETTINGS'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->select('system_editor')
                    ->setText('SYSTEM_OPTIONS_NEWS_EDITOR')
                    ->setOptions($editors)
                    ->setSelected(base64_encode($globalConfig->system_editor))
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); 
                ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
        <?php $theView->select('system_editor_fontsize')
            ->setText('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE')
                ->setOptions($defaultFontsizes)
                ->setSelected($globalConfig->system_editor_fontsize)
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-6">
        <?php $theView->boolSelect('articles_revisions')
                ->setText('SYSTEM_OPTIONS_REVISIONS_ENABLED')
                ->setSelected($globalConfig->articles_revisions); ?>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-6">
            <?php $theView->select('articles_revisions_limit')
                    ->setText('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT')
                    ->setOptions($theView->translate('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT_LIST'))
                    ->setSelected($globalConfig->articles_revisions_limit)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-6">
            <?php $theView->boolSelect('articles_imageedit_persistence')
                    ->setText('SYSTEM_OPTIONS_NEWS_EDITOR_IMGTOOLS')
                    ->setSelected($globalConfig->articles_imageedit_persistence); ?>
        </div>
    </div>

</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_CSS'); ?></legend>

    <div class="row">
        <div class="col-12 col-md-6">
                <?php $theView->textarea('system_editor_css')
                        ->setValue($globalConfig->system_editor_css, ENT_QUOTES)
                        ->setClass('fpcm ui-textarea-medium ui-textarea-noresize w-100'); ?>
        </div>
    </div>
</fieldset>


<fieldset class="mb-2">
    <legend><?php $theView->write('HL_FILES_MNG'); ?></legend>                       
    <div class="row my-2">
        <div class="col-12 col-md-6">
        <?php $theView->boolSelect('file_subfolders')
                ->setText('SYSTEM_OPTIONS_NEWS_SUBFOLDERS')
                ->setSelected($globalConfig->file_subfolders); ?>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-6">
        <?php $theView->select('file_list_limit')
                ->setText('SYSTEM_OPTIONS_FILEMANAGER_LIMIT')
                ->setOptions($articleLimitListAcp)
                ->setSelected($globalConfig->file_list_limit)
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-6">
            <?php $theView->select('file_view')
                    ->setText('SYSTEM_OPTIONS_FILEMANAGER_VIEW')
                    ->setOptions($filemanagerViews)
                    ->setSelected($globalConfig->file_view)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>                     
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_NEWSSHOWIMGTHUMBSIZE'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-6">
        <?php $theView->numberInput('file_thumb_size')
                ->setText('FILE_LIST_THUMB_SIZE')
                ->setValue($globalConfig->file_thumb_size)
                ->setMaxlenght(5)
                ->setMin(50)
                ->setMax(400); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6 justify-content-center text-center">
            <img id="fpcm-thumb-preview" title="<?php $theView->write('GLOBAL_PREVIEW'); ?>" class="mb-3 rounded bg-info" src="<?php print $theView->themePath; ?>logo.svg" role="presentation" style="width:<?php print $globalConfig->file_thumb_size; ?>px;height: <?php print $globalConfig->file_thumb_size; ?>px;">
        </div>
    </div>

</fieldset>