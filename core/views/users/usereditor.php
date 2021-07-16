<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row g-0">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>
            
            <div class="row py-2 g-0">
                <div class="col-12 col-md-6">
                    <div class="row">
                        <?php $theView->textInput('data[displayname]')
                            ->setValue($author->getDisplayName())
                            ->setAutocomplete(false)
                            ->setText('USERS_DISPLAYNAME')
                            ->setIcon('signature'); ?>
                    </div>
                </div>
            </div>
            
            <div class="row py-2 g-0">
                <div class="col-12 col-md-6">
                    <div class="row">
                        <?php $theView->textInput('data[username]')
                            ->setValue($author->getUserName())
                            ->setReadonly((isset($inProfile) && $inProfile))
                            ->setAutocomplete(false)
                            ->setText('GLOBAL_USERNAME')
                            ->setIcon('user'); ?>
                    </div>
                </div>
            </div>
            
            <div class="row g-0">
                <div class="col-12 col-md-6 align-self-center">
                    <div class="row">
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
            
            <div class="row py-2 g-0">
                <div class="col-12 col-md-6">
                    <div class="row">
                        <?php $theView->textInput('data[password_confirm]', 'password_confirm')
                            ->setAutocomplete(false)
                            ->setText('USERS_PASSWORD_CONFIRM')
                            ->setIcon('passport')
                            ->setPattern('^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$'); ?>
                    </div>
                </div>
            </div>
            
            <div class="row py-2 g-0">
                <div class="col-12 col-md-6">
                    <div class="row">
                        <?php $theView->textInput('data[email]')
                            ->setType('email')
                            ->setValue($author->getEmail())
                            ->setText('GLOBAL_EMAIL')
                            ->setIcon('at'); ?>
                    </div>
                </div>
            </div>
            
            <?php if($inProfile) : ?>           
            <div id="fpcm-ui-currentpass-box" class="row py-2 g-0 fpcm-ui-hidden">
                <div class="col-12 col-md-6">
                    <div class="row">
                        <?php $theView->passwordInput('data[current_pass]')
                            ->setAutocomplete(false)
                            ->setText('GLOBAL_PASSWORD_CONFIRM')
                            ->setIcon('exclamation-triangle text-danger')
                            ->setSize('lg'); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="row py-2 g-0 <?php print $inProfile ? 'fpcm-ui-hidden' : '' ?>">
                <div class="col-12 col-md-6">
                    <div class="row">
                            <?php $theView->select('data[roll]')
                                    ->setOptions($userRolls)
                                    ->setSelected($author->getRoll())
                                    ->setReadonly((isset($inProfile) && $inProfile))
                                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                                    ->setText('USERS_ROLL')
                                    ->setIcon('users')
                                    ->prependLabel(); ?>
                    </div>
                </div>
            </div>
            
            <?php if ($showDisableButton) : ?>
            <div class="row py-2 g-0 <?php print $inProfile ? 'fpcm-ui-hidden' : '' ?>">
                <div class="col-12 col-md-6">
                    <div class="row">
                        <?php $theView->boolSelect('data[disabled]')
                                ->setSelected($author->getDisabled())
                                    ->setText('GLOBAL_DISABLE')
                                    ->setIcon('user-slash')
                                    ->prependLabel(); ?>

                    </div>
                </div>
            </div>
            <?php endif; ?>
        </fieldset>
    </div>
</div>

<?php if ($twoFaAuth) : ?>
<div class="row g-0">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('SYSTEM_OPTIONS_LOGIN_TWOFACTORAUTH'); ?></legend>

            <div class="row g-0 mb-3">
            <?php if ($secret !== false && $qrCode !== false) : ?>
                <div class="col-12 col-md align-self-center">
                    <div class="m-3" id="user_profile_image_buttons">
                        <?php $theView->textInput('data[authCodeConfirm]', 'authCodeConfirm')
                            ->setValue('')
                            ->setMaxlenght(6)
                            ->setAutocomplete(false)
                            ->setText('USERS_AUTHTOKEN_SAVE2')
                            ->setLabelClass('pe-3')
                            ->setIcon('exclamation-triangle text-danger')
                            ->setSize('lg'); ?>                    

                        <?php $theView->hiddenInput('data[authSecret]', 'authSecret')->setValue($secret); ?>
                    </div>

                </div>
                <div class="col-12 col-md align-self-center">
                    <?php $theView->linkButton('openQr')
                            ->setUrl($qrCode)
                            ->setClass('fpcm ui-link-fancybox')
                            ->setIcon('qrcode'); ?>
                </div>                        
            <?php else: ?>
                <div class="col-12 col-md align-self-center">
                    <div class="m-3" id="user_profile_image_buttons">
                    <?php $theView->icon('user-secret')->setStack('check fpcm-ui-editor-metainfo fpcm ui-status-075')->setSize('lg')->setStackTop(true); ?>
                    <?php $theView->write('USERS_AUTHTOKEN_ACTIVE'); ?>
                    </div>

                </div>
                <div class="col-12 col-md align-self-center">
                    <?php $theView->checkbox('disable2Fa')->setText('GLOBAL_DISABLE'); ?>
                </div>
            <?php endif; ?>
            </div>
        </fieldset>
    </div>
</div>
<?php endif; ?>