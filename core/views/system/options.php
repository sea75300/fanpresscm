<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-8">
                <?php $theView->textInput('system_email')
                    ->setValue($globalConfig->system_email)                                        
                    ->setText('GLOBAL_EMAIL')
                    ->setType('email'); ?>

        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
                <?php $theView->textInput('system_url')
                    ->setValue($globalConfig->system_url)                                        
                    ->setText('SYSTEM_OPTIONS_URL')
                    ->setType('url'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
            <div class="row g-0">
                <?php $theView->textInput('system_dtmask')
                    ->setValue($globalConfig->system_dtmask)                                        
                    ->setText('SYSTEM_OPTIONS_DATETIMEMASK'); ?>
            </div>
        </div>
        <div class="col-auto align-self-center mx-3 mb-3">
            <?php $theView->shorthelpButton('dtmask')
                    ->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')
                    ->setUrl('http://php.net/manual/function.date.php'); ?>                    
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
                <?php $theView->select('system_timezone')
                        ->setText('SYSTEM_OPTIONS_TIMEZONE')
                        ->setOptions($timezoneAreas)
                        ->setSelected($globalConfig->system_timezone)
                        ->setOptGroup(true); ?>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->select('system_lang')
                ->setText('SYSTEM_OPTIONS_LANG')
                ->setOptions($languages)
                ->setSelected($globalConfig->system_lang)
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-8">
            <?php $theView->select('articles_acp_limit')
                    ->setText('SYSTEM_OPTIONS_ACPARTICLES_LIMIT')
                    ->setOptions($articleLimitListAcp)
                    ->setSelected($globalConfig->articles_acp_limit)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-8">
            <?php $theView->select('system_cache_timeout')
                    ->setText('SYSTEM_OPTIONS_CACHETIMEOUT')
                    ->setOptions($theView->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'))
                    ->setSelected($globalConfig->system_cache_timeout)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-8">
                <?php $theView->select('system_trash_cleanup')
                        ->setText('SYSTEM_OPTIONS_TRASH_CLEANUP_DAYS')
                        ->setOptions($theView->translate('SYSTEM_OPTIONS_TRASH_CLEANUP_DAYS_LIST'))
                        ->setSelected($globalConfig->system_trash_cleanup)
                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('HL_FRONTEND'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-8">
            <?php $theView->textInput('system_css_path')
                    ->setValue($globalConfig->system_css_path, ENT_QUOTES)
                    ->setText($theView->translate('SYSTEM_OPTIONS_STYLESHEET'))
                    ->setType('url')
                    ->setPlaceholder('http://'.$_SERVER['HTTP_HOST'].'/style/style.css'); ?>
        </div>
    </div>

    <div class="row g-0 my-2">
        <div class="col-12 col-md-8">
            <div class="row row-cols-1 row-cols-xl-2">
                <div class="col">
                    <?php $theView->select('system_mode')
                        ->setText('SYSTEM_OPTIONS_USEMODE')
                        ->setOptions($systemModes)
                        ->setSelected($globalConfig->system_mode)
                        ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                </div>

                <div class="col">
                    <div class="row g-0">
                        <div class="col flex-grow-1">
                            <?php $theView->boolSelect('system_loader_jquery')
                                ->setText('SYSTEM_OPTIONS_INCLUDEJQUERY')
                                ->setSelected($globalConfig->system_loader_jquery); ?>
                        </div>
                        <div class="col-auto align-self-center mx-3 mb-3">
                            <?php $theView->shorthelpButton('jqueryInclude')->setText('SYSTEM_OPTIONS_INCLUDEJQUERY_YES'); ?>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</fieldset>