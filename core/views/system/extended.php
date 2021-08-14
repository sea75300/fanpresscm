<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_HL_OPTIONS_SECURITY'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->boolSelect('system_maintenance')->setText('SYSTEM_OPTIONS_MAINTENANCE')->setSelected($globalConfig->system_maintenance); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->select('system_session_length')
                ->setOptions($theView->translate('SYSTEM_OPTIONS_SESSIONLENGHT_INTERVALS'))
                ->setText('SYSTEM_OPTIONS_SESSIONLENGHT')
                ->setSelected($globalConfig->system_session_length)
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->numberInput('system_loginfailed_locked')
                ->setText('SYSTEM_OPTIONS_LOGIN_MAXATTEMPTS')
                ->setValue($globalConfig->system_loginfailed_locked)
                ->setMaxlenght(5); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->boolSelect('system_2fa_auth')->setText('SYSTEM_OPTIONS_LOGIN_TWOFACTORAUTH')->setSelected($globalConfig->system_2fa_auth); ?>
        </div>
    </div>


    <div class="row my-2">
        <div class="col-12 col-md-8">
            <div class="row g-0">
                <?php $theView->boolSelect('system_passcheck_enabled')
                        ->setText('SYSTEM_OPTIONS_USERS_PASSCHECK')
                        ->setSelected($globalConfig->system_passcheck_enabled); ?>
            </div>
        </div>
        <div class="col-auto align-self-center mx-3 mb-3">
            <?php $theView->shorthelpButton('pwndpass')->setText('GLOBAL_OPENNEWWIN')->setUrl('https://haveibeenpwned.com/passwords'); ?>
        </div>
    </div>
</fieldset>

<fieldset class="my-2" >
    <legend><?php $theView->write('SYSTEM_OPTIONS_EXTENDED_UPDATES'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->boolSelect('system_updates_emailnotify')->setText('SYSTEM_OPTIONS_EXTENDED_EMAILUPDATES')->setSelected($globalConfig->system_updates_emailnotify); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->boolSelect('system_updates_devcheck')->setText('SYSTEM_OPTIONS_EXTENDED_DEVUPDATES')->setSelected($globalConfig->system_updates_devcheck); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->select('system_updates_manual')
                ->setOptions($theView->translate('SYSTEM_OPTIONS_UPDATESMANUAL'))
                ->setText('SYSTEM_OPTIONS_EXTENDED_UPDATESMANCHK')
                ->setSelected($globalConfig->system_updates_manual)
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div>
</fieldset>