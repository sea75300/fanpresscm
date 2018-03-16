<div class="row no-gutters fpcm-ui-position-absolute fpcm-ui-position-absolute-0">
    <div class="col-sm-12 col-md-5 fpcm-ui-margin-center align-self-center">
        <div class="ui-widget-content ui-corner-all ui-state-normal fpcm-ui-padding-md-tb">

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12">
                    <?php $theView->textInput($userNameField)->setText('GLOBAL_USERNAME')->setPlaceholder(true); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12">
                <?php if ($resetPasswort) : ?>
                    <?php $theView->textInput('email')->setText('GLOBAL_EMAIL')->setPlaceholder(true); ?>
                <?php else : ?>
                    <?php $theView->passwordInput('login[password]')->setText('GLOBAL_PASSWORD')->setPlaceholder(true); ?>
                <?php endif; ?>
                </div>
            </div>

            <div class="fpcm-ui-margin-center fpcm-ui-margintop-md fpcm-ui-marginbottom-md fpcm-ui-center">
                <div class="fpcm-ui-controlgroup">
            <?php if ($resetPasswort) : ?>
                <?php $theView->submitButton('reset')->setText('GLOBAL_OK')->setClass('fpcm-loader fpcm-ok-button')->setIcon('check'); ?>
                <?php $theView->linkButton('loginback')->setText('GLOBAL_BACK')->setUrl($theView->self.'?module='.$theView->currentModule)->setClass('fpcm-loader fpcm-back-button')->setIcon('chevron-circle-left'); ?>
            <?php else : ?>
                <?php $theView->submitButton('login')->setText('LOGIN_BTN')->setClass('fpcm-loader fpcm-login-btn')->setIcon('sign-in'); ?>
                <?php $theView->linkButton('newpass')->setText('LOGIN_NEWPASSWORD')->setUrl($theView->self.'?module='.$theView->currentModule.'&reset')->setClass('fpcm-loader fpcm-passreset-btn')->setIcon('key'); ?>
            <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>