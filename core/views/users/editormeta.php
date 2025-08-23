<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row g-0 gap-2">
    <div class="col">
        <fieldset class="mb-2">
            <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

            <div class="row my-2 row-cols-1 row-cols-xl-2">
                <div class="col">
                    <?php $theView->select('usermeta[system_timezone]')
                            ->setOptions($timezoneAreas)
                            ->setSelected($author->getUserMeta()->system_timezone)
                            ->setOptGroup(true)
                            ->setText('SYSTEM_OPTIONS_TIMEZONE')
                            ->setLabelTypeFloat()
                            ->setIcon('globe'); ?>
                </div>
                <div class="col">
                    <div class="row g-0">
                        <div class="col flex-grow-1">
                        <?php $theView->textInput('usermeta[system_dtmask]')
                            ->setValue($author->getUserMeta()->system_dtmask)
                            ->setAutocomplete(false)
                            ->setText('SYSTEM_OPTIONS_DATETIMEMASK')
                            ->setPlaceholder('SYSTEM_OPTIONS_DATETIMEMASK')
                            ->setLabelTypeFloat()
                            ->setIcon('calendar'); ?>
                        </div>
                        <div class="col-auto align-self-center mx-3 mb-3">
                        <?php $theView->shorthelpButton('dtmask')
                            ->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')
                            ->setUrl('http://php.net/manual/function.date.php'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row my-2">
                <div class="col">
                    <?php $theView->select('usermeta[system_lang]')
                                ->setOptions($languages)
                                ->setSelected($author->getUserMeta()->system_lang)
                                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                                ->setText('SYSTEM_OPTIONS_LANG')
                            ->setLabelTypeFloat()
                                ->setIcon('language'); ?>
                </div>
            </div>

            <div class="row my-2">
                <div class="col">
                <?php $theView->select('usermeta[articles_acp_limit]')
                            ->setOptions($articleLimitList)
                            ->setSelected($author->getUserMeta()->articles_acp_limit)
                            ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                            ->setText('SYSTEM_OPTIONS_ACPARTICLES_LIMIT')
                            ->setLabelTypeFloat()
                            ->setIcon('list'); ?>
                </div>
                <div class="col">
                    <?php $theView->boolSelect('usermeta[system_darkmode]')
                        ->setSelected($author->getUserMeta()->system_darkmode)
                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                        ->setText('SYSTEM_OPTIONS_DARKMODE')
                        ->setLabelTypeFloat()
                        ->setIcon('moon'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <?php $theView->select('usermeta[backdrop]')
                        ->setOptions(array_combine($backdrops, $backdrops))
                        ->setSelected($author->getUserMeta()->backdrop)
                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                        ->setText('SYSTEM_OPTIONS_BACKDROP_IMAGE')
                        ->setLabelTypeFloat()
                        ->setIcon('panorama'); ?>
                </div>

            </div>
        </fieldset>
    </div>
    
    <div class="col">
        <fieldset class="mb-2">
            <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_SETTINGS'); ?> / <?php $theView->write('HL_FILES_MNG'); ?></legend>

            <div class="row my-2 row-cols-1 row-cols-xl-2">
                <div class="col">
                    <?php $theView->select('usermeta[system_editor_fontsize]')
                        ->setOptions($defaultFontsizes)
                        ->setSelected($author->getUserMeta()->system_editor_fontsize)
                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                        ->setText('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE')
                        ->setLabelTypeFloat()
                        ->setIcon('text-height'); ?>
                </div>
            </div>

            <div class="row my-2 row-cols-1 row-cols-xl-2">
                <div class="col">
                    <?php $theView->select('usermeta[file_list_limit]')
                        ->setOptions($articleLimitList)
                        ->setSelected($author->getUserMeta()->file_list_limit)
                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                        ->setText('SYSTEM_OPTIONS_FILEMANAGER_LIMIT')
                        ->setLabelTypeFloat()
                        ->setIcon('folder-open'); ?>
                </div>
                <div class="col">
                    <?php $theView->select('usermeta[file_view]')
                        ->setOptions($filemanagerViews)
                        ->setSelected($author->getUserMeta()->file_view)
                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                        ->setText('SYSTEM_OPTIONS_FILEMANAGER_VIEW')
                        ->setLabelTypeFloat()
                        ->setIcon('grip-horizontal'); ?>
                </div>
            </div>
        </fieldset>  
    </div>
</div>