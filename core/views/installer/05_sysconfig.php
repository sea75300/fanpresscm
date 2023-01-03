<?php /* @var $theView fpcm\view\viewVars */ ?>
<h4 class="mb-3"><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></h4>

<div class="row g-0 my-2">
    <div class="col">
    <?php $theView->textInput('conf[system_email]')                             
        ->setText('GLOBAL_EMAIL')
        ->setPlaceholder('GLOBAL_EMAIL')
        ->setLabelTypeFloat()
        ->setType('email')
        ->setRequired(true); ?>
    </div>
</div>
<div class="row g-0 my-2">
    <div class="col">
    <?php $theView->textInput('conf[system_url]')
        ->setValue(fpcm\classes\dirs::getRootUrl('index.php'))                                        
        ->setText('SYSTEM_OPTIONS_URL')
        ->setPlaceholder('SYSTEM_OPTIONS_URL')
        ->setLabelTypeFloat()
        ->setType('url')
        ->setRequired(true); ?>
    </div>
</div>

<div class="row row-cols-3 gap-3 g-0 my-2">
    <div class="col">    
    <?php $theView->select('conf[system_lang]')
            ->setOptions($languages)
            ->setSelected($theView->langCode)
            ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
            ->setText('SYSTEM_OPTIONS_LANG')
            ->setLabelTypeFloat(); ?>
    </div>

    <div class="col">       
    <?php $theView->select('conf[system_cache_timeout]')
            ->setOptions($theView->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'))
            ->setSelected(FPCM_CACHE_DEFAULT_TIMEOUT)
            ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
            ->setText('SYSTEM_OPTIONS_CACHETIMEOUT')
            ->setLabelTypeFloat(); ?>
    </div>
</div>

<div class="row row-cols-3 gap-3 g-0 my-2">
    <div class="col">      
    <?php $theView->select('conf[system_timezone]')
            ->setOptions($timezoneAreas)
            ->setSelected('Europe/Berlin')
            ->setOptGroup(true)
            ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
            ->setText('SYSTEM_OPTIONS_TIMEZONE')
            ->setLabelTypeFloat(); ?>
    </div>

    <div class="col">
        <div class="row g-0">
            <?php $theView->textInput('conf[system_dtmask]', 'system_dtmask')
                ->setValue('d.m.Y H:i:s')                                        
                ->setText('SYSTEM_OPTIONS_DATETIMEMASK')
                ->setPlaceholder('SYSTEM_OPTIONS_DATETIMEMASK')
                ->setRequired(true)
                ->setLabelTypeFloat(); ?>
        </div>
    </div>
    <div class="col-auto align-self-center mb-3">
        <?php $theView->shorthelpButton('dtmask')
                ->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')
                ->setUrl('http://php.net/manual/function.date.php'); ?>                    
    </div>
</div>

<div class="row row-cols-3 gap-3 g-0 my-2">
    <div class="col">
    <?php $theView->select('conf[system_mode]')
            ->setOptions($systemModes)
            ->setSelected(1)
            ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
            ->setText('SYSTEM_OPTIONS_USEMODE')
            ->setLabelTypeFloat(); ?>
    </div>
    <div class="col">
        <div class="row g-0">
            <?php $theView->boolSelect('conf[system_loader_jquery]')
            ->setText('SYSTEM_OPTIONS_INCLUDEJQUERY')
            ->setSelected(1)
            ->setLabelTypeFloat(); ?>
        </div>
    </div>
    <div class="col-auto align-self-center mb-3">
        <?php $theView->shorthelpButton('jqueryInclude')->setText('SYSTEM_OPTIONS_INCLUDEJQUERY_YES'); ?>
    </div>
</div>

<h4 class="mb-3"><?php $theView->write('SYSTEM_OPTIONS_CAPTCHASETTING'); ?></h4>

<div class="row row-cols-3 gap-3 g-0 my-2">

    <div class="col">
    <?php $theView->textInput('conf[comments_antispam_question]')                                        
        ->setText('SYSTEM_OPTIONS_ANTISPAMQUESTION')
        ->setPlaceholder('SYSTEM_OPTIONS_ANTISPAMQUESTION')
        ->setRequired(true)
        ->setLabelTypeFloat(); ?>
    </div>

    <div class="col">
    <?php $theView->textInput('conf[comments_antispam_answer]')
        ->setText('SYSTEM_OPTIONS_ANTISPAMANSWER')
        ->setPlaceholder('SYSTEM_OPTIONS_ANTISPAMANSWER')
        ->setRequired(true)
        ->setLabelTypeFloat(); ?>
    </div>
</div>