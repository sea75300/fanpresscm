<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>
            
            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12 col-sm-6 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                            <?php $theView->icon('globe'); ?>
                            <?php $theView->write('SYSTEM_OPTIONS_TIMEZONE'); ?>:
                        </label>
                        <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                            <?php $theView->select('usermeta[system_timezone]')
                                    ->setOptions($timezoneAreas)
                                    ->setSelected($author->getUserMeta('system_timezone'))
                                    ->setOptGroup(true); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12 col-sm-6 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                            <?php $theView->icon('language'); ?>
                            <?php $theView->write('SYSTEM_OPTIONS_LANG'); ?>:
                        </label>
                        <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                            <?php $theView->select('usermeta[system_lang]')
                                    ->setOptions($languages)
                                    ->setSelected($author->getUserMeta('system_lang'))
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12 col-sm-6 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                            <?php $theView->icon('list'); ?>
                            <?php $theView->write('SYSTEM_OPTIONS_ACPARTICLES_LIMIT'); ?>:
                        </label>
                        <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                            <?php $theView->select('usermeta[articles_acp_limit]')
                                    ->setOptions($articleLimitList)
                                    ->setSelected($author->getUserMeta('articles_acp_limit'))
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12 col-sm-6 col-lg-5">
                    <div class="row">
                        <?php $theView->textInput('data[system_dtmask]')
                                ->setValue($author->getUserMeta('system_dtmask'))
                                ->setAutocomplete(false)
                                ->setText('SYSTEM_OPTIONS_DATETIMEMASK')
                                ->setIcon('calendar')
                                ->setDisplaySizes(['xs' => 12, 'sm' => 6], ['xs' => 12, 'sm' => 6, 'lg' => 4]); ?>

                        <?php $theView->shorthelpButton('dtmask')
                                ->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')
                                ->setUrl('http://php.net/manual/function.date.php')
                                ->setClass('col-12 col-sm-auto align-self-center'); ?>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div class="row no-gutters">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-md-top">
            <legend><?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_SETTINGS'); ?> / <?php $theView->write('HL_FILES_MNG'); ?></legend>

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12 col-sm-6 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                            <?php $theView->icon('text-height'); ?>
                            <?php $theView->write('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE'); ?>:
                        </label>
                        <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                            <?php $theView->select('usermeta[system_editor_fontsize]')
                                ->setOptions($defaultFontsizes)
                                ->setSelected($author->getUserMeta('system_editor_fontsize'))
                                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>
                    </div>
                </div>
            </div>            

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12 col-sm-6 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                            <?php $theView->icon('file-upload'); ?>
                            <?php $theView->write('SYSTEM_OPTIONS_NEWS_NEWUPLOADER'); ?>:
                        </label>
                        <div class="col-12 col-sm-6 fpcm-ui-padding-none-lr">
                            <?php $theView->boolSelect('usermeta[file_uploader_new]')->setSelected($author->getUserMeta('file_uploader_new')); ?>
                        </div>
                        <div class="col px-0 align-self-center">
                            <?php $theView->icon('skull-crossbones')->setText('GLOBAL_DEPRECATED')->setSize('lg'); ?>
                        </div>
                    </div>
                </div>
            </div>            

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12 col-sm-6 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                            <?php $theView->icon('folder-open'); ?>
                            <?php $theView->write('SYSTEM_OPTIONS_FILEMANAGER_LIMIT'); ?>:
                        </label>
                        <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                            <?php $theView->select('usermeta[file_list_limit]')
                                    ->setOptions($articleLimitList)
                                    ->setSelected($author->getUserMeta('file_list_limit'))
                                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>
                    </div>
                </div>
            </div>            

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12 col-sm-6 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                            <?php $theView->icon('grip-horizontal'); ?>
                            <?php $theView->write('SYSTEM_OPTIONS_FILEMANAGER_VIEW'); ?>:
                        </label>
                        <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                            <?php $theView->select('usermeta[file_view]')
                            ->setOptions($filemanagerViews)
                            ->setSelected($author->getUserMeta('file_view'))
                            ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div class="row no-gutters">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-md-top">
            <legend><?php $theView->write('HL_DASHBOARD'); ?></legend>
            
            <div class="row fpcm-ui-padding-md-tb">
                <div class="align-self-center col-12 fpcm-ui-padding-none-lr">        
                    <?php $theView->submitButton('resetDashboardSettings')->setText('USERS_META_RESET_DASHBOARD')->setIcon('undo'); ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>