<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_TWITTER_CREDENTIALS'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-8">
            <?php $theView->boolSelect('smtp_enabled')->setText('SYSTEM_OPTIONS_EMAIL_ENABLED')->setSelected($globalConfig->smtp_enabled); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->textInput('smtp_settings[addr]')
                ->setType('email')
                ->setValue($globalConfig->smtp_settings->addr)
                ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                ->setText('GLOBAL_EMAIL')
                ->setPlaceholder('mail@example.com')
                ->setClass('fpcm-ui-options-smtp-input'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->textInput('smtp_settings[srvurl]')
                ->setValue($globalConfig->smtp_settings->srvurl)
                ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                ->setText('SYSTEM_OPTIONS_EMAIL_SERVER')
                ->setPlaceholder('mail.example.com')
                ->setClass('fpcm-ui-options-smtp-input'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->textInput('smtp_settings[port]')
                ->setValue($globalConfig->smtp_settings->port)
                ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                ->setText('SYSTEM_OPTIONS_EMAIL_PORT')
                ->setPlaceholder('25')
                ->setClass('fpcm-ui-options-smtp-input'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->textInput('smtp_settings[user]')
                ->setValue($globalConfig->smtp_settings->user)
                ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                ->setText('SYSTEM_OPTIONS_EMAIL_USERNAME')
                ->setPlaceholder('mail@example.com')
                ->setClass('fpcm-ui-options-smtp-input'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->passwordInput('smtp_settings[pass]')
                ->setText('SYSTEM_OPTIONS_EMAIL_PASSWORD')
                ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                ->setClass('fpcm-ui-options-smtp-input')
                ->setPlaceholder(trim($globalConfig->smtp_settings->pass) ? '*****' : ''); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->select('smtp_settings[encr]')
                ->setOptions($smtpEncryption)
                ->setText('SYSTEM_OPTIONS_EMAIL_ENCRYPTED')
                ->setSelected($globalConfig->smtp_settings->encr)
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setReadonly(($globalConfig->smtp_enabled ? false : true)); ?>
        </div>
    </div>
</fieldset>