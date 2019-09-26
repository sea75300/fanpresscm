<?php /* @var $theView fpcm\view\viewVars */ ?>         
<div class="col-12 col-sm-8 col-md-6">
    <div class="row no-gutters">
        <div class="col-12">
            <fieldset>
                <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

                <div class="row fpcm-ui-padding-md-tb no-gutters">
                    <div class="col-12">
                        <div class="row">
                            <?php $theView->textInput('conf[system_email]')                             
                                ->setWrapper(false)
                                ->setText('GLOBAL_EMAIL')
                                ->setType('email')
                                ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                                ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
                        </div>
                    </div>
                </div>                
                
                <div class="row fpcm-ui-padding-md-tb no-gutters">
                    <div class="col-12">
                        <div class="row">
                            <?php $theView->textInput('conf[system_url]')
                                ->setValue(fpcm\classes\dirs::getRootUrl('index.php'))                                        
                                ->setWrapper(false)
                                ->setText('SYSTEM_OPTIONS_URL')
                                ->setType('url')
                                ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                                ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
                        </div>
                    </div>
                </div>

                <div class="row fpcm-ui-padding-md-tb">
                    <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                        <?php $theView->write('SYSTEM_OPTIONS_LANG'); ?>
                    </label>
                    <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                        <?php $theView->select('conf[system_lang]')
                                ->setOptions($languages)
                                ->setSelected($theView->langCode)
                                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                    </div>
                </div>

                <div class="row fpcm-ui-padding-md-tb">
                    <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                        <?php $theView->write('SYSTEM_OPTIONS_TIMEZONE'); ?>
                    </label>
                    <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                        <?php $theView->select('conf[system_timezone]')
                                ->setOptions($timezoneAreas)
                                ->setSelected('Europe/Berlin')
                                ->setOptGroup(true); ?>
                    </div>
                </div>
                
                <div class="row fpcm-ui-padding-md-tb no-gutters">
                    <div class="col-12">
                        <div class="row">
                            <?php $theView->textInput('conf[system_dtmask]')
                                ->setValue('d.m.Y H:i:s')                                        
                                ->setWrapper(false)
                                ->setText('SYSTEM_OPTIONS_DATETIMEMASK')
                                ->setClass('col-12 col-md-5 fpcm-ui-field-input-nowrapper-general')
                                ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>

                            <div class="align-self-center col-sm-1 col-md-1 fpcm-ui-padding-md-lr">
                                <?php $theView->shorthelpButton('dtmask')->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')->setUrl('http://php.net/manual/function.date.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row fpcm-ui-padding-md-tb">
                    <div class="align-self-center col-sm-12 col-md-5 fpcm-ui-field-label-general">
                        <?php $theView->write('SYSTEM_OPTIONS_SESSIONLENGHT'); ?>
                    </div>
                    <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                        <?php $theView->select('conf[system_session_length]')
                                ->setOptions($theView->translate('SYSTEM_OPTIONS_SESSIONLENGHT_INTERVALS'))
                                ->setSelected(3600)
                                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                    </div>
                </div>

                <div class="row fpcm-ui-padding-md-tb">
                    <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                        <?php $theView->write('SYSTEM_OPTIONS_CACHETIMEOUT'); ?>
                    </label>
                    <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                        <?php $theView->select('conf[system_cache_timeout]')
                                ->setOptions($theView->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'))
                                ->setSelected(FPCM_CACHE_DEFAULT_TIMEOUT)
                                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                    </div>
                </div>

                <div class="row fpcm-ui-padding-md-tb">
                    <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                        <?php $theView->write('SYSTEM_OPTIONS_USEMODE'); ?>
                    </label>
                    <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                        <?php $theView->select('conf[system_mode]')->setOptions($systemModes)->setSelected(1)->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>

    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-12">
            <fieldset>
                <legend><?php $theView->write('SYSTEM_OPTIONS_CAPTCHASETTING'); ?></legend>

                <div class="row fpcm-ui-padding-md-tb no-gutters">
                    <div class="col-12">
                        <div class="row">
                            <?php $theView->textInput('conf[comments_antispam_question]')                                        
                                ->setWrapper(false)
                                ->setText('SYSTEM_OPTIONS_ANTISPAMQUESTION')
                                ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                                ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
                        </div>
                    </div>
                </div>

                <div class="row fpcm-ui-padding-md-tb no-gutters">
                    <div class="col-12">
                        <div class="row">
                            <?php $theView->textInput('conf[comments_antispam_answer]')
                                ->setWrapper(false)
                                ->setText('SYSTEM_OPTIONS_ANTISPAMANSWER')
                                ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                                ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</div>