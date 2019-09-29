<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row no-gutters">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>
            
            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12 col-sm-6">
                    <div class="row">
                        <?php $theView->textInput('data[displayname]')
                            ->setValue($author->getDisplayName())
                            ->setAutocomplete(false)
                            ->setWrapper(false)
                            ->setText('USERS_DISPLAYNAME')
                            ->setIcon('signature')
                            ->setDisplaySizes([12, 5], [12, 7]); ?>
                    </div>
                </div>
            </div>
            
            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12 col-sm-6">
                    <div class="row">
                        <?php $theView->textInput('data[username]')
                            ->setValue($author->getUserName())
                            ->setReadonly((isset($inProfile) && $inProfile))
                            ->setAutocomplete(false)
                            ->setWrapper(false)
                            ->setText('GLOBAL_USERNAME')
                            ->setIcon('user')
                            ->setDisplaySizes([12, 5], [12, 7]); ?>
                    </div>
                </div>
            </div>
            
            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12 col-sm-6">
                    <div class="row">
                        <?php $theView->textInput('data[password]', 'password')
                            ->setAutocomplete(false)
                            ->setWrapper(false)
                            ->setText('GLOBAL_PASSWORD')
                            ->setIcon('passport')
                            ->setDisplaySizes([12, 5], [12, 7]); ?>
                    </div>
                </div>
                <div class="col-12 col-sm-auto mt-2 ml-0 mt-sm-0 ml-sm-3">
                    <?php $theView->button('genPasswd', 'genPasswd')->setText('USERS_PASSGEN')->setIcon('key')->setIconOnly(true); ?>
                    <?php $theView->shorthelpButton('dtmask')->setText('USERS_REQUIREMENTS'); ?>
                </div>
            </div>
            
            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12 col-sm-6">
                    <div class="row">
                        <?php $theView->textInput('data[password_confirm]', 'password_confirm')
                            ->setAutocomplete(false)
                            ->setWrapper(false)
                            ->setText('USERS_PASSWORD_CONFIRM')
                            ->setIcon('passport')
                            ->setDisplaySizes([12, 5], [12, 7]); ?>
                    </div>
                </div>
            </div>
            
            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12 col-sm-6">
                    <div class="row">
                        <?php $theView->textInput('data[email]')
                            ->setType('email')
                            ->setValue($author->getEmail())
                            ->setWrapper(false)
                            ->setText('GLOBAL_EMAIL')
                            ->setIcon('at')
                            ->setDisplaySizes([12, 5], [12, 7]); ?>
                    </div>
                </div>
            </div>
            
            <div class="row fpcm-ui-padding-md-tb <?php print $inProfile ? 'fpcm-ui-hidden' : '' ?>">
                <div class="col-12 col-sm-6 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                            <?php $theView->icon('users'); ?>
                            <?php $theView->write('USERS_ROLL'); ?>:
                        </label>
                        <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                            <?php $theView->select('data[roll]')
                                    ->setOptions($userRolls)
                                    ->setSelected($author->getRoll())
                                    ->setReadonly((isset($inProfile) && $inProfile))
                                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if($inProfile) : ?>
            
            <div id="fpcm-ui-currentpass-box" class="row no-gutters fpcm-ui-padding-md-tb fpcm-ui-hidden">
                <div class="col-12 col-sm-6">
                    <div class="row">
                        <?php $theView->passwordInput('data[current_pass]')
                            ->setAutocomplete(false)
                            ->setWrapper(false)
                            ->setText('GLOBAL_PASSWORD_CONFIRM')
                            ->setIcon('exclamation-triangle fpcm-ui-important-text')
                            ->setSize('lg')
                            ->setDisplaySizes([12, 5], [12, 7]); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($showDisableButton) : ?>
            <div class="row fpcm-ui-padding-md-tb <?php print $inProfile ? 'fpcm-ui-hidden' : '' ?>">
                <div class="col-12 col-sm-6 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-5 fpcm-ui-field-label-general">
                            <?php $theView->icon('user-slash'); ?>
                            <?php $theView->write('GLOBAL_DISABLE'); ?>:
                        </label>
                        <div class="col-12 col-sm-7 fpcm-ui-padding-none-lr">
                            <?php $theView->boolSelect('data[disabled]')->setSelected($author->getDisabled()); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </fieldset>
    </div>
</div>

<?php if ($twoFaAuth) : ?>
<div class="row no-gutters">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-md-top">
            <legend><?php $theView->write('SYSTEM_OPTIONS_LOGIN_TWOFACTORAUTH'); ?></legend>

            <?php if ($secret !== false && $qrCode !== false) : ?>
            <div class="row no-gutters fpcm-ui-padding-md-tb">
                <div class="col-12">
                    <?php $theView->write('USERS_AUTHTOKEN_SAVE'); ?>:
                </div>
            </div>

            <div class="row no-gutters fpcm-ui-padding-md-tb">
                <div class="col-12 col-sm-6">
                    <div class="row">
                        <?php $theView->textInput('data[authCodeConfirm]', 'authCodeConfirm')
                            ->setValue('')
                            ->setMaxlenght(6)
                            ->setAutocomplete(false)
                            ->setWrapper(false)
                            ->setText('USERS_AUTHTOKEN_SAVE2')
                            ->setIcon('exclamation-triangle fpcm-ui-important-text')
                            ->setSize('lg')
                            ->setDisplaySizes([12, 5], [12, 5]); ?>                    

                        <?php $theView->hiddenInput('data[authSecret]', 'authSecret')->setValue($secret); ?>
                    </div>
                </div>
            </div>
            <div class="row no-gutters fpcm-ui-padding-md-tb fpcm-ui-center">
                <div class="col-12 col-sm-6">
                    <img src="<?php echo $qrCode; ?>">
                </div>
            </div>
            <?php else : ?>
            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12">
                    <?php $theView->icon('user-secret')->setStack('check fpcm-ui-editor-metainfo fpcm-ui-status-075')->setSize('lg')->setStackTop(true); ?>
                    <?php $theView->write('USERS_AUTHTOKEN_ACTIVE'); ?>
                    <?php $theView->checkbox('disable2Fa')->setText('GLOBAL_DISABLE'); ?>
                </div>
            </div>
            <?php endif; ?>
        </fieldset>
    </div>
</div>
<?php endif; ?>