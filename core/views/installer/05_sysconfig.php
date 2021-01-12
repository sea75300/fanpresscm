<?php /* @var $theView fpcm\view\viewVars */ ?>         
<div class="col-12">
    <fieldset>
        <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

        <div class="row my-2">
            <?php $theView->textInput('conf[system_email]')                             
                ->setText('GLOBAL_EMAIL')
                ->setType('email')
                ->setDisplaySizesDefault(); ?>
        </div>                

        <div class="row my-2">
            <?php $theView->textInput('conf[system_url]')
                ->setValue(fpcm\classes\dirs::getRootUrl('index.php'))                                        
                ->setText('SYSTEM_OPTIONS_URL')
                ->setType('url')
                ->setDisplaySizesDefault(); ?>
        </div>

        <div class="row my-2 fpcm-ui-selectmenu-border-radius-right">        
            <?php $theView->select('conf[system_lang]')
                    ->setOptions($languages)
                    ->setSelected($theView->langCode)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('SYSTEM_OPTIONS_LANG')
                    ->setDisplaySizesDefault()
                    ->prependLabel(); ?>
        </div>

        <div class="row my-2 fpcm-ui-selectmenu-border-radius-right">        
            <?php $theView->select('conf[system_timezone]')
                    ->setOptions($timezoneAreas)
                    ->setSelected('Europe/Berlin')
                    ->setOptGroup(true)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('SYSTEM_OPTIONS_TIMEZONE')
                    ->setDisplaySizesDefault()
                    ->prependLabel(); ?>
        </div>

        <div class="row my-2">
            <?php $theView->textInput('conf[system_dtmask]')
                ->setValue('d.m.Y H:i:s')                                        
                ->setText('SYSTEM_OPTIONS_DATETIMEMASK')
                ->setDisplaySizes(['xs' => 12, 'sm' => 5, 'md' => 3], ['xs' => 12, 'sm' => 5, 'md' => 8]); ?>

                <div class="align-self-center col-md-1 fpcm-ui-padding-md-lr">
                    <?php $theView->shorthelpButton('dtmask')->setText('SYSTEM_OPTIONS_DATETIMEMASK_HELP')->setUrl('http://php.net/manual/function.date.php'); ?>
                </div>

        </div>

        <div class="row my-2 fpcm-ui-selectmenu-border-radius-right">        
            <?php $theView->select('conf[system_session_length]')
                    ->setOptions($theView->translate('SYSTEM_OPTIONS_SESSIONLENGHT_INTERVALS'))
                    ->setSelected(3600)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('SYSTEM_OPTIONS_SESSIONLENGHT')
                    ->setDisplaySizesDefault()
                    ->prependLabel(); ?>
        </div>

        <div class="row my-2 fpcm-ui-selectmenu-border-radius-right">        
            <?php $theView->select('conf[system_cache_timeout]')
                    ->setOptions($theView->translate('SYSTEM_OPTIONS_CACHETIMEOUT_INTERVAL'))
                    ->setSelected(FPCM_CACHE_DEFAULT_TIMEOUT)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('SYSTEM_OPTIONS_CACHETIMEOUT')
                    ->setDisplaySizesDefault()
                    ->prependLabel(); ?>
        </div>

        <div class="row my-2 fpcm-ui-selectmenu-border-radius-right">        
            <?php $theView->select('conf[system_mode]')
                    ->setOptions($systemModes)
                    ->setSelected(1)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('SYSTEM_OPTIONS_USEMODE')
                    ->setDisplaySizesDefault()
                    ->prependLabel(); ?>
        </div>

    </fieldset>
</div>

<div class="col-12 my-2">
    <fieldset>
        <legend><?php $theView->write('SYSTEM_OPTIONS_CAPTCHASETTING'); ?></legend>

        <div class="row my-2">
            <?php $theView->textInput('conf[comments_antispam_question]')                                        
                ->setText('SYSTEM_OPTIONS_ANTISPAMQUESTION')
                ->setDisplaySizesDefault(); ?>
        </div>

        <div class="row my-2">
            <?php $theView->textInput('conf[comments_antispam_answer]')
                ->setText('SYSTEM_OPTIONS_ANTISPAMANSWER')
                ->setDisplaySizesDefault(); ?>
        </div>
    </fieldset>
</div>