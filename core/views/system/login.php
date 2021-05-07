<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row g-0 fpcm-ui-full-view-height m-2 ms-0">
    <div class="fpcm ui-form-login col-12 col-md-10 col-lg-8 col-xl-5 fpcm-ui-margin-center align-self-center">
        <div class="fpcm ui-background-white-50p ui-blurring ui-box-shadow-dark ui-border-radius-all p-3 py-md-3 px-md-4">

            <header>
                <h1 class="fpcm-ui-margin-md-bottom"><?php $theView->icon('chevron-right'); ?> <span>FanPress CM</span> <span>News System</span></h1>
                <!-- FanPress CM News System <?php print $theView->version; ?> -->
            </header>
            
            <?php if ($twoFactorAuth) : ?>
            <div class="row g-0 fpcm-ui-padding-md-tb">
                <div class="col-12">
                <?php $theView->textInput('login[authcode]')->setText('LOGIN_AUTHCODE')
                        ->setMaxlenght(6)->setPlaceholder(true)->setAutocomplete(false)
                        ->setAutoFocused(true)->setWrapper(true)->setClass('fpcm-ui-monospace'); ?>
                <?php $theView->hiddenInput('login[formData]')->setValue($formData); ?>
                </div>
            </div>
            <?php else : ?>
            <div class="row g-0 fpcm-ui-padding-md-tb">
                <div class="col-12">
                    <?php $theView->textInput($userNameField)->setText('GLOBAL_USERNAME')->setPlaceholder(true)->setAutocomplete(false)->setAutoFocused(true)->setWrapper(true); ?>
                </div>
            </div>

            <div class="row g-0 fpcm-ui-padding-md-tb">
                <div class="col-12">
                <?php if ($resetPasswort) : ?>
                    <?php $theView->textInput('email')->setType('email')->setText('GLOBAL_EMAIL')->setPlaceholder(true)->setAutocomplete(false)->setWrapper(true); ?>
                <?php else : ?>
                    <?php $theView->passwordInput('login[password]')->setText('GLOBAL_PASSWORD')->setPlaceholder(true)->setAutocomplete(false)->setWrapper(true); ?>
                <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($resetPasswort) : ?>
            <div class="row fpcm-ui-padding-md-tb">
                <?php print $captcha->createPluginTextInput(); ?>
            </div>
            <?php endif; ?>

            <div class="fpcm-ui-margin-center fpcm-ui-margin-md-top fpcm-ui-margin-md-bottom fpcm-ui-center">
            <?php if ($resetPasswort) : ?>
                <?php $theView->submitButton('reset')->setText('GLOBAL_OK')->setClass('fpcm-loader fpcm-ok-button')->setIcon('check'); ?>
                <?php $theView->linkButton('loginback')->setText('GLOBAL_BACK')->setUrl($theView->controllerLink('system/login'))->setClass('fpcm-loader fpcm-back-button')->setIcon('chevron-circle-left'); ?>
            <?php elseif ($twoFactorAuth) : ?>
                <?php $theView->submitButton('login')->setText('GLOBAL_OK')->setClass('fpcm-loader fpcm-login-btn')->setIcon('sign-in-alt'); ?>
                <?php $theView->linkButton('loginback')->setText('GLOBAL_BACK')->setUrl($theView->controllerLink('system/login'))->setClass('fpcm-loader fpcm-back-button')->setIcon('chevron-circle-left'); ?>
            <?php else : ?>
                <?php $theView->submitButton('login')->setText('LOGIN_BTN')->setClass('fpcm-loader fpcm-login-btn')->setIcon('sign-in-alt'); ?>
                <?php $theView->linkButton('newpass')->setText('LOGIN_NEWPASSWORD')->setUrl($theView->controllerLink('system/login', ['reset' => 1]))->setClass('fpcm-loader fpcm-passreset-btn')->setIcon('passport'); ?>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>