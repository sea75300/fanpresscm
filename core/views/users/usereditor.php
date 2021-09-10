<?php /* @var $theView fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->textInput('data[displayname]')
                    ->setValue($author->getDisplayName())
                    ->setAutocomplete(false)
                    ->setAutoFocused(true)
                    ->setText('USERS_DISPLAYNAME')
                    ->setIcon('signature'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->textInput('data[username]')
                    ->setValue($author->getUserName())
                    ->setReadonly((isset($inProfile) && $inProfile))
                    ->setAutocomplete(false)
                    ->setText('GLOBAL_USERNAME')
                    ->setIcon('user'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6 align-self-center">
            <div class="row g-0">
                <?php $theView->textInput('data[password]', 'password')
                    ->setAutocomplete(false)
                    ->setText('GLOBAL_PASSWORD')
                    ->setIcon('passport')
                    ->setPattern('^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$'); ?>
            </div>
        </div>
        <div class="col-auto align-self-center mx-3 mb-3">
            <?php $theView->button('genPasswd', 'genPasswd')->setText('USERS_PASSGEN')->setIcon('key')->setIconOnly(true); ?>
        </div>
        <div class="col-auto align-self-center mx-3 mb-3">
            <?php $theView->shorthelpButton('dtmask')->setText('USERS_REQUIREMENTS'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->textInput('data[password_confirm]', 'password_confirm')
                    ->setAutocomplete(false)
                    ->setText('USERS_PASSWORD_CONFIRM')
                    ->setIcon('passport')
                    ->setPattern('^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->textInput('data[email]')
                    ->setType('email')
                    ->setValue($author->getEmail())
                    ->setText('GLOBAL_EMAIL')
                    ->setIcon('at'); ?>
        </div>
    </div>

    <?php if($inProfile) : ?>           
    <div id="fpcm-ui-currentpass-box" class="row my-2 fpcm-ui-hidden">
        <div class="col-12 col-md-6">
                <?php $theView->passwordInput('data[current_pass]')
                    ->setAutocomplete(false)
                    ->setText('GLOBAL_PASSWORD_CONFIRM')
                    ->setIcon('exclamation-triangle text-danger')
                    ->setSize('lg'); ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="row my-2 <?php print $inProfile ? 'fpcm-ui-hidden' : '' ?>">
        <div class="col-12 col-md-6">
            <?php $theView->select('data[roll]')
                    ->setOptions($userRolls)
                    ->setSelected($author->getRoll())
                    ->setReadonly((isset($inProfile) && $inProfile))
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('USERS_ROLL')
                    ->setIcon('users'); ?>
        </div>
    </div>

    <?php if ($showDisableButton) : ?>
    <div class="row my-2 <?php print $inProfile ? 'fpcm-ui-hidden' : '' ?>">
        <div class="col-12 col-md-6">
                <?php $theView->boolSelect('data[disabled]')
                        ->setSelected($author->getDisabled())
                            ->setText('GLOBAL_DISABLE')
                            ->setIcon('user-slash'); ?>

        </div>
    </div>
    <?php endif; ?>
</fieldset>

<?php if ($twoFaAuth) : ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_OPTIONS_LOGIN_TWOFACTORAUTH'); ?></legend>
    
    <div class="row my-2">
    <?php if ($secret !== false && $qrCode !== false) : ?>
        <div class="col-12 col-md-6 align-self-center">
            <?php $theView->textInput('data[authCodeConfirm]', 'authCodeConfirm')
                ->setValue('')
                ->setMaxlenght(6)
                ->setAutocomplete(false)
                ->setText('USERS_AUTHTOKEN_SAVE')
                ->setPlaceholder($theView->translate('LOGIN_AUTHCODE'))
                ->setLabelClass('pe-3')
                ->setIcon('exclamation-triangle text-danger')
                ->setSize('lg'); ?>                    

            <?php $theView->hiddenInput('data[authSecret]', 'authSecret')->setValue($secret); ?>
        </div>
        <div class="col-12 col-md-auto align-self-center mb-3">
            <?php $theView->linkButton('openQr')
                    ->setUrl($qrCode)
                    ->setIcon('qrcode'); ?>
        </div>                        
    <?php else: ?>
        <div class="col-12 col-md-6 align-self-center">
            <?php $theView->icon('user-secret')->setStack('check fpcm-ui-editor-metainfo opacity-75')->setSize('lg')->setStackTop(true); ?>
            <?php $theView->write('USERS_AUTHTOKEN_ACTIVE'); ?>
        </div>
        <div class="col-12 col-md-auto align-self-center">
            <?php $theView->checkbox('disable2Fa')->setText('GLOBAL_DISABLE'); ?>
        </div>
    <?php endif; ?>
    </div>
</fieldset>
<?php endif; ?>