<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="col-12 col-sm-8">
    <div class="row fpcm-ui-padding-md-tb fpcm-ui-padding-md-tb">
        <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
            <?php $theView->write('GLOBAL_EMAIL'); ?>:
        </div>
        <div class="align-self-center col-sm-12 col-md-4">
            <?php $theView->textInput('conf[system_email]'); ?>		
        </div>
        <div class="align-self-center col-sm-12 col-md-auto">
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
            <?php $theView->write('SYSTEM_OPTIONS_URL'); ?>:
        </div>
        <div class="align-self-center col-sm-12 col-md-4">
            <?php $theView->textInput('conf[system_url]')->setValue(fpcm\classes\dirs::getRootUrl('index.php')); ?>
        </div>
        <div class="align-self-center col-sm-12 col-md-auto">
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
            <?php $theView->write('SYSTEM_OPTIONS_LANG'); ?>:
        </div>
        <div class="align-self-center col-sm-12 col-md-4">
            <?php $theView->select('conf[system_lang]')->setOptions($languages)->setSelected($theView->langCode)->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="align-self-center col-sm-12 col-md-auto">
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
            <?php $theView->write('SYSTEM_OPTIONS_TIMEZONE'); ?>:
        </div>
        <div class="align-self-center col-sm-12 col-md-4">
            <?php $theView->select('conf[system_timezone]')->setOptions($timezoneAreas)->setSelected('Europe/Berlin')->setOptGroup(true); ?>
        </div>
        <div class="align-self-center col-sm-12 col-md-auto">
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
            <?php $theView->write('SYSTEM_OPTIONS_DATETIMEMASK'); ?>:
        </div>
        <div class="align-self-center col-sm-11 col-md-4">
            <?php $theView->textInput('conf[system_dtmask]')->setValue('d.m.Y H:i:s'); ?>
        </div>
        <div class="align-self-center col-sm-1 col-md-auto fpcm-ui-padding-md-lr">
            <?php $theView->shorthelpButton('dtmask')->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')->setUrl('http://php.net/manual/function.date.php'); ?>
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
            <?php $theView->write('SYSTEM_OPTIONS_SESSIONLENGHT'); ?>:
        </div>
        <div class="align-self-center col-sm-12 col-md-4">
            <?php $theView->select('conf[system_session_length]')
                    ->setOptions($theView->translate('SYSTEM_OPTIONS_SESSIONLENGHT_INTERVALS'))
                    ->setSelected(3600)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="align-self-center col-sm-12 col-md-auto">
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
            <?php $theView->write('SYSTEM_OPTIONS_CACHETIMEOUT'); ?>:
        </div>
        <div class="align-self-center col-sm-12 col-md-4">
            <?php $theView->select('conf[system_cache_timeout]')->setOptions($theView->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'))->setSelected(FPCM_CACHE_DEFAULT_TIMEOUT)->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="align-self-center col-sm-12 col-md-auto">
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
            <?php $theView->write('SYSTEM_OPTIONS_USEMODE'); ?>:
        </div>
        <div class="align-self-center col-sm-12 col-md-4">
            <?php $theView->select('conf[system_mode]')->setOptions($systemModes)->setSelected(1)->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
        <div class="align-self-center col-sm-12 col-md-auto">
        </div>
    </div>        

    <div class="row fpcm-ui-padding-md-tb">
        <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
            <?php $theView->write('SYSTEM_OPTIONS_ANTISPAMQUESTION'); ?>:
        </div>
        <div class="align-self-center col-sm-12 col-md-4">
            <?php $theView->textInput('conf[comments_antispam_question]'); ?>		
        </div>
        <div class="align-self-center col-sm-12 col-md-auto">
        </div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="align-self-center col-sm-12 col-md-4 fpcm-ui-padding-none-lr">
            <?php $theView->write('SYSTEM_OPTIONS_ANTISPAMANSWER'); ?>:
        </div>
        <div class="align-self-center col-sm-12 col-md-4">
            <?php $theView->textInput('conf[comments_antispam_answer]'); ?>
        </div>
        <div class="align-self-center col-sm-12 col-md-auto">
        </div>
    </div>
</div>