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
    
    <?php if ($globalConfig->smtp_settings->auth != 'XOAUTH2') : ?>
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

    <div class="row my-2 border-5 border-danger">
        <div class="col-12 col-md-8">
        <?php $theView->passwordInput('smtp_settings[pass]')
                ->setText('SYSTEM_OPTIONS_EMAIL_PASSWORD')
                ->setReadonly(($globalConfig->smtp_enabled ? false : true))
                ->setClass('fpcm-ui-options-smtp-input')
                ->setPlaceholder(trim($globalConfig->smtp_settings->pass) ? '*****' : ''); ?>
        </div>
    </div>
        <?php endif; ?>

    <div class="row g-0 my-2">
        <div class="col-12 col-md-8">
            <div class="row row-cols-1 row-cols-sm-2">
            <?php if ($globalConfig->smtp_settings->auth != 'XOAUTH2') : ?>
                <div class="col">
                <?php $theView->select('smtp_settings[encr]')
                    ->setOptions($smtpEncryption)
                    ->setText('SYSTEM_OPTIONS_EMAIL_ENCRYPTED')
                    ->setSelected($globalConfig->smtp_settings->encr)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setReadonly(($globalConfig->smtp_enabled ? false : true)); ?>
                    
                </div>
            <?php endif; ?>

                <div class="col">
                <?php $theView->select('smtp_settings[auth]')
                    ->setOptions($smtpAuthTypes)
                    ->setText('SYSTEM_OPTIONS_EMAIL_AUTHTYPE')
                    ->setSelected($globalConfig->smtp_settings->auth)
                    ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setReadonly(($globalConfig->smtp_enabled ? false : true)); ?>
                </div>
            </div>            
        </div>
    </div>    

    <?php if ($globalConfig->smtp_settings->auth == 'XOAUTH2') : ?>

    <div class="row my-2">
        <div class="col-12 col-md-8">
            <div class="input-group">
                <div class="col-12 col-md-4">
                    <?php $theView->write('SYSTEM_OPTIONS_TWITTER_USER_SECRET'); ?>
                </div>            

                <div class="col-12 col-md-8 p-0">
                    <?php $theView->textarea('smtp_settings[token]')
                        ->setValue($globalConfig->smtp_settings->token)
                        ->setClass('fpcm ui-textarea-medium ui-textarea-noresize w-100'); ?>
                </div>
            </div>
        </div>
    </div>    
    
    
    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->linkButton('oauth-auth')->setUrl('')->setText('SYSTEM_OPTIONS_EMAIL_OAUTH'); ?>
        </div>
    </div>
    <?php endif; ?>
</fieldset>