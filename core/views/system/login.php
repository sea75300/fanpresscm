<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="position-absolute top-50 start-50 translate-middle col-12 col-md-8 col-lg-6 col-xl-4">
    <div class="shadow-lg rounded p-4 fpcm ui-background-white-50p ui-blurring">

        <header>
            <div class="row g-0 mb-3">
                <!-- FanPress CM News System <?php print $theView->version; ?> -->
                <div class="col-auto">
                    <img class="border-bottom border-5 border-info" src="<?php print $theView->themePath; ?>logo.svg" role="presentation" alt="FanPress CM News System <?php print $theView->version; ?>">
                </div>
                <div class="col align-self-center">
                    <h1 class="d-none d-xl-block fs-3">FanPress CM News System</h1>
                </div>
                
            </div>
            
        </header>

        <?php if ($twoFactorAuth) : ?>
        <div class="row g-0">
            <?php $theView->textInput('login[authcode]')->setText('LOGIN_AUTHCODE')
                    ->setMaxlenght(6)->setPlaceholder(true)->setAutocomplete(false)
                    ->setAutoFocused(true)->setWrapper(true)->setClass('fpcm-ui-monospace'); ?>
            <?php $theView->hiddenInput('login[formData]')->setValue($formData); ?>
        </div>
        <?php else : ?>
        <div class="row g-0">
            <?php $theView->textInput($userNameField)->setText('GLOBAL_USERNAME')->setPlaceholder(true)->setAutocomplete(false)->setAutoFocused(true)->setWrapper(true); ?>
        </div>

        <div class="row g-0">
            <?php if ($resetPasswort) : ?>
                <?php $theView->textInput('email')->setType('email')->setText('GLOBAL_EMAIL')->setPlaceholder(true)->setAutocomplete(false)->setWrapper(true); ?>
            <?php else : ?>
                <?php $theView->passwordInput('login[password]')->setText('GLOBAL_PASSWORD')->setPlaceholder(true)->setAutocomplete(false)->setWrapper(true); ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($resetPasswort) : ?>
        <div class="row g-0">
            <?php print $captcha->createPluginTextInput(); ?>
        </div>
        <?php endif; ?>

        <div class="row row-cols-auto gap-2 justify-content-center">
        <?php if ($resetPasswort) : ?>
            <?php $theView->submitButton('reset')->setText('GLOBAL_OK')->setIcon('check')->setPrimary(); ?>
            <?php $theView->linkButton('loginback')->setText('GLOBAL_BACK')->setUrl($theView->controllerLink('system/login'))->setIcon('chevron-circle-left'); ?>
        <?php elseif ($twoFactorAuth) : ?>
            <?php $theView->submitButton('login')->setText('GLOBAL_OK')->setIcon('sign-in-alt')->setPrimary(); ?>
            <?php $theView->linkButton('loginback')->setText('GLOBAL_BACK')->setUrl($theView->controllerLink('system/login'))->setIcon('chevron-circle-left'); ?>
        <?php else : ?>
            <?php $theView->submitButton('login')->setText('LOGIN_BTN')->setIcon('sign-in-alt')->setPrimary(); ?>
            <?php $theView->linkButton('newpass')->setText('LOGIN_NEWPASSWORD')->setUrl($theView->controllerLink('system/login', ['reset' => 1]))->setIcon('passport'); ?>
        <?php endif; ?>
        </div>
    </div>
</div>