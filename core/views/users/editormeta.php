<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-6">
            <?php $theView->select('usermeta[system_timezone]')
                    ->setOptions($timezoneAreas)
                    ->setSelected($author->getUserMeta('system_timezone'))
                    ->setOptGroup(true)
                    ->setText('SYSTEM_OPTIONS_TIMEZONE')
                    ->setIcon('globe'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
            <?php $theView->select('usermeta[system_lang]')
                        ->setOptions($languages)
                        ->setSelected($author->getUserMeta('system_lang'))
                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                        ->setText('SYSTEM_OPTIONS_LANG')
                        ->setIcon('language'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->select('usermeta[articles_acp_limit]')
                            ->setOptions($articleLimitList)
                            ->setSelected($author->getUserMeta('articles_acp_limit'))
                            ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                            ->setText('SYSTEM_OPTIONS_ACPARTICLES_LIMIT')
                            ->setIcon('list'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
            <div class="row g-0">
                <?php $theView->textInput('usermeta[system_dtmask]')
                        ->setValue($author->getUserMeta('system_dtmask'))
                        ->setAutocomplete(false)
                        ->setText('SYSTEM_OPTIONS_DATETIMEMASK')
                        ->setIcon('calendar'); ?>
            </div>
        </div>
        <div class="col-auto align-self-center mx-3 mb-3">
            <?php $theView->shorthelpButton('dtmask')
                    ->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')
                    ->setUrl('http://php.net/manual/function.date.php'); ?>

        </div>
    </div>            
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_SETTINGS'); ?> / <?php $theView->write('HL_FILES_MNG'); ?></legend>
  
    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->select('usermeta[system_editor_fontsize]')
                        ->setOptions($defaultFontsizes)
                        ->setSelected($author->getUserMeta('system_editor_fontsize'))
                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                        ->setText('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE')
                        ->setIcon('text-height'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->select('usermeta[file_list_limit]')
                            ->setOptions($articleLimitList)
                            ->setSelected($author->getUserMeta('file_list_limit'))
                            ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                            ->setText('SYSTEM_OPTIONS_FILEMANAGER_LIMIT')
                            ->setIcon('folder-open'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->select('usermeta[file_view]')
                                ->setOptions($filemanagerViews)
                                ->setSelected($author->getUserMeta('file_view'))
                                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                                ->setText('SYSTEM_OPTIONS_FILEMANAGER_VIEW')
                                ->setIcon('grip-horizontal'); ?>
        </div>
    </div>    

</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('HL_DASHBOARD'); ?></legend>

    <div class="m-2">        
        <?php $theView->submitButton('resetDashboardSettings')->setText('USERS_META_RESET_DASHBOARD')->setIcon('undo')->setClass('fpcm ui-button-confirm'); ?>
    </div>
</fieldset>