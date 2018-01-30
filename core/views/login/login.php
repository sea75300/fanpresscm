<div class="fpcm-content-wrapper">
    <div class="fpcm-login-form">
    <?php if (!isset($lockedGlobal)) : ?>
        <?php if ($loginAttempts < $loginAttemptsMax) : ?>
        <div class="ui-widget-content ui-corner-all ui-state-normal">
            <form method="post" action="<?php print $theView->basePath; ?>system/login">
                <table class="fpcm-ui-table fpcm-login-form-table">
                    <tr>
                        <td><?php (new fpcm\view\helper\textInput($userNameField))->setText('GLOBAL_USERNAME')->setPlaceholder(true); ?></td>
                    </tr>
                    <tr>
                        <td>
                        <?php if ($resetPasswort) : ?>
                            <?php (new fpcm\view\helper\textInput('email'))->setText('GLOBAL_EMAIL')->setPlaceholder(true); ?>
                        <?php else : ?>
                            <?php (new fpcm\view\helper\passwordInput('login[password]'))->setText('GLOBAL_PASSWORD')->setPlaceholder(true); ?>
                        <?php endif; ?>
                        </td>
                </table>

                
                <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                <?php if ($resetPasswort) : ?>
                    <?php (new \fpcm\view\helper\submitButton('reset'))->setText('GLOBAL_OK')->setClass('fpcm-loader fpcm-ok-button')->setIcon('fa fa-check fa-fw'); ?>
                    <?php (new fpcm\view\helper\linkButton('loginback'))->setText('GLOBAL_BACK')->setUrl($theView->self.'?module='.$theView->currentModule)->setClass('fpcm-loader fpcm-back-button')->setIcon('fa fa-chevron-circle-left fa-fw'); ?>
                <?php else : ?>
                    <?php (new \fpcm\view\helper\submitButton('login'))->setText('LOGIN_BTN')->setClass('fpcm-loader fpcm-login-btn')->setIcon('fa fa-sign-in fa-fw'); ?>
                    <?php (new fpcm\view\helper\linkButton('newpass'))->setText('LOGIN_NEWPASSWORD')->setUrl($theView->self.'?module='.$theView->currentModule.'&reset')->setClass('fpcm-loader fpcm-passreset-btn')->setIcon('fa fa-key fa-fw'); ?>
                <?php endif; ?>
                    </div>
                </div>
                
                <?php \fpcm\view\helper::pageTokenField(); ?>
                
            </form> 
        </div>
        <?php endif; ?>    
    <?php endif; ?>
    </div>
</div>