<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-full-view-height m-2 ms-0">
    <div class="fpcm ui-form-login col-12 col-md-10 col-lg-8 col-xl-5 fpcm-ui-margin-center align-self-center">
        <div class="fpcm ui-background-white-50p ui-blurring fpcm-ui-border-radius-all p-3 py-md-3 px-md-4">

            <header>
                <h1 class="fpcm-ui-margin-md-bottom"><?php $theView->icon('chevron-right'); ?> <span>FanPress CM</span> <span>News System</span></h1>
                <!-- FanPress CM News System <?php print $theView->version; ?> -->
            </header>
            
            <?php if ($twoFactorAuth) : ?>
            <div class="row no-gutters fpcm-ui-padding-md-tb">
                <div class="col-12">
                <?php $theView->textInput('login[authcode]')->setText('LOGIN_AUTHCODE')->setPlaceholder(true)->setAutocomplete(false)->setAutoFocused(true)->setWrapper(true); ?>
                <?php $theView->hiddenInput('login[username]')->setValue($username); ?>
                <?php $theView->hiddenInput('login[password]')->setValue($password); ?>
                </div>
            </div>
            <?php else : ?>
            <div class="row no-gutters fpcm-ui-padding-md-tb">
                <div class="col-12">
                    <?php $theView->textInput($userNameField)->setText('GLOBAL_USERNAME')->setPlaceholder(true)->setAutocomplete(false)->setAutoFocused(true)->setWrapper(true); ?>
                </div>
            </div>

            <div class="row no-gutters fpcm-ui-padding-md-tb">
                <div class="col-12">
                <?php if ($resetPasswort) : ?>
                    <?php $theView->textInput('email')->setType('email')->setText('GLOBAL_EMAIL')->setPlaceholder(true)->setAutocomplete(false)->setAutoFocused(true)->setWrapper(true); ?>
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
                <div class="fpcm-ui-controlgroup">
            <?php if ($resetPasswort) : ?>
                <?php $theView->submitButton('reset')->setText('GLOBAL_OK')->setClass('fpcm-loader fpcm-ok-button')->setIcon('check'); ?>
                <?php $theView->linkButton('loginback')->setText('GLOBAL_BACK')->setUrl($theView->self.'?module='.$theView->currentModule)->setClass('fpcm-loader fpcm-back-button')->setIcon('chevron-circle-left'); ?>
            <?php elseif ($twoFactorAuth) : ?>
                <?php $theView->submitButton('login')->setText('GLOBAL_OK')->setClass('fpcm-loader fpcm-login-btn')->setIcon('sign-in-alt'); ?>
                <?php $theView->linkButton('loginback')->setText('GLOBAL_BACK')->setUrl($theView->self.'?module='.$theView->currentModule)->setClass('fpcm-loader fpcm-back-button')->setIcon('chevron-circle-left'); ?>
            <?php else : ?>
                <?php $theView->submitButton('login')->setText('LOGIN_BTN')->setClass('fpcm-loader fpcm-login-btn')->setIcon('sign-in-alt'); ?>
                <?php $theView->linkButton('newpass')->setText('LOGIN_NEWPASSWORD')->setUrl($theView->self.'?module='.$theView->currentModule.'&reset')->setClass('fpcm-loader fpcm-passreset-btn')->setIcon('passport'); ?>
            <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>