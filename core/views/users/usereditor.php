<?php /* @var $theView fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->textInput('data[displayname]')
                    ->setValue($author->getDisplayName())
                    ->setAutocomplete(false)
                    ->setAutoFocused(true)
                    ->setRequired(true)
                    ->setText('USERS_DISPLAYNAME')
                    ->setPlaceholder('USERS_DISPLAYNAME')
                    ->setLabelTypeFloat()
                    ->setIcon('signature'); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->textInput('data[username]')
                    ->setValue($author->getUserName())
                    ->setReadonly((isset($inProfile) && $inProfile))
                    ->setAutocomplete(false)
                    ->setRequired(true)
                    ->setText('GLOBAL_USERNAME')
                    ->setPlaceholder('GLOBAL_USERNAME')
                    ->setLabelTypeFloat()                        
                    ->setIcon('user'); ?>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-4 gap-1 my-2">
        <div class="col align-self-center">
                <?php $theView->textInput('data[password]', 'password')
                    ->setAutocomplete(false)
                    ->setText('GLOBAL_PASSWORD')
                    ->setPlaceholder('GLOBAL_PASSWORD')
                    ->setLabelTypeFloat()
                    ->setIcon('passport')
                    ->setRequired(!$author->getId())
                    ->setPattern('^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$'); ?>
        </div>
        <div class="col ps-md-0">
                <?php $theView->textInput('data[password_confirm]', 'password_confirm')
                    ->setAutocomplete(false)
                    ->setText('USERS_PASSWORD_CONFIRM')
                    ->setPlaceholder('USERS_DISPLAYNAME')
                    ->setLabelTypeFloat()
                    ->setIcon('passport')
                    ->setRequired(!$author->getId())
                    ->setPattern('^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$'); ?>
        </div>
        <div class="col align-self-center mb-3">
            <div class="d-flex justify-content-center justify-content-md-start">                
                <?php $theView->button('genPasswd', 'genPasswd')->setText('USERS_PASSGEN')->setIcon('key')->setIconOnly(); ?>&nbsp;
                <?php $theView->shorthelpButton('pass')->setText('USERS_REQUIREMENTS'); ?>
            </div>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-6">
                <?php $theView->textInput('data[email]')
                    ->setType('email')
                    ->setValue($author->getEmail())
                    ->setText('GLOBAL_EMAIL')
                    ->setPlaceholder('GLOBAL_EMAIL')
                    ->setLabelTypeFloat()
                    ->setRequired(true)
                    ->setIcon('at'); ?>
        </div>
    </div>

    <?php if($inProfile) : ?>
    <div id="fpcm-ui-currentpass-box" class="row my-2 fpcm-ui-hidden">
        <div class="col-12 col-md-6">
                <?php $theView->passwordInput('data[current_pass]')
                    ->setAutocomplete(false)
                    ->setText('GLOBAL_PASSWORD_CONFIRM')
                    ->setPlaceholder('GLOBAL_PASSWORD_CONFIRM')
                    ->setLabelTypeFloat()
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
                    ->setLabelTypeFloat()
                    ->setIcon('users'); ?>
        </div>
    </div>

    <?php if ($showDisableButton) : ?>
    <div class="row my-2 <?php print $inProfile ? 'fpcm-ui-hidden' : '' ?>">
        <div class="col-12 col-md-6">
                <?php $theView->boolSelect('data[disabled]')
                ->setSelected($author->getDisabled())
                ->setText('GLOBAL_DISABLE')
                ->setLabelTypeFloat()
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
                ->setPlaceholder('USERS_AUTHTOKEN_SAVE')
                ->setLabelTypeFloat()
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
            <?php $theView->alert('success')->setText('USERS_AUTHTOKEN_ACTIVE')->setIcon('user-secret'); ?>
        </div>
        <div class="col-12 col-md-auto align-self-center">
            <?php $theView->checkbox('disable2Fa')->setText('GLOBAL_DISABLE')->setSwitch(true); ?>
        </div>
    <?php endif; ?>
    </div>
</fieldset>
<?php endif; ?>

<?php if(!$inProfile && $author->getId()) : ?>
<fieldset class="my-2">
    <legend class="fpcm-ui-font-small"><?php $theView->write('GLOBAL_METADATA'); ?></legend>

    <div class="row g-0 my-2 fpcm-ui-font-small">
        <div class="col-12 col-md-6">
            
            <div class="row mb-1 row-cols-2">
                <div class="col">
                    <?php $theView->icon('calendar')->setSize('lg'); ?>
                    <strong><?php $theView->write('USERS_REGISTEREDTIME'); ?>:</strong>
                </div>
                <div class="col">
                    <?php print $createInfo; ?>
                </div>
            </div>
            
            <div class="row mb-1row-cols-2">
                <div class="col">
                    <?php $theView->icon('clock', 'far')->setSize('lg'); ?> 
                    <strong><?php $theView->write('GLOBAL_LASTCHANGE'); ?>:</strong>
                </div>
                <div class="col">
                    <?php print $changeInfo; ?>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<?php endif; ?>