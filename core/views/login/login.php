<div class="fpcm-content-wrapper">
    <div class="col-sm-12 col-md-8 fpcm-login-form fpcm-ui-margin-center">
    <?php if (!isset($lockedGlobal)) : ?>
        <?php if ($showLoginForm) : ?>
        <div class="ui-widget-content ui-corner-all ui-state-normal">
            <form method="post" action="<?php print $theView->basePath; ?>system/login">
                <table class="fpcm-ui-table fpcm-login-form-table">
                    <tr>
                        <td><?php $theView->textInput($userNameField)->setText('GLOBAL_USERNAME')->setPlaceholder(true); ?></td>
                    </tr>
                    <tr>
                        <td>
                        <?php if ($resetPasswort) : ?>
                            <?php $theView->textInput('email')->setText('GLOBAL_EMAIL')->setPlaceholder(true); ?>
                        <?php else : ?>
                            <?php $theView->passwordInput('login[password]')->setText('GLOBAL_PASSWORD')->setPlaceholder(true); ?>
                        <?php endif; ?>
                        </td>
                </table>

                
                <div class="fpcm-ui-margin-center fpcm-ui-margintop-md fpcm-ui-marginbottom-md">
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
                
                <?php $theView->pageTokenField('pgtkn'); ?>
                
            </form> 
        </div>
        <?php endif; ?>    
    <?php endif; ?>
    </div>
</div>