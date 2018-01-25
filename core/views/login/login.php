<div class="fpcm-content-wrapper">
    <div class="fpcm-login-form">
    <?php if (!isset($lockedGlobal)) : ?>
        <?php if ($loginAttempts < $loginAttemptsMax) : ?>
        <div class="ui-widget-content ui-corner-all ui-state-normal">
            <form method="post" action="<?php print $theView->basePath; ?>system/login">
                <table class="fpcm-ui-table fpcm-login-form-table">
                    <tr>
                        <td>
                        <?php if ($resetPasswort) : ?>
                            <?php \fpcm\view\helper::textInput('username', '', '', false, 255, $theView->lang->translate('GLOBAL_USERNAME')); ?>
                        <?php else : ?>
                            <?php \fpcm\view\helper::textInput('login[username]', '', '', false, 255, $theView->lang->translate('GLOBAL_USERNAME')); ?>
                        <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <?php if ($resetPasswort) : ?>
                            <?php \fpcm\view\helper::textInput('email', '', '', false, 255, $theView->lang->translate('GLOBAL_EMAIL')); ?>
                        <?php else : ?>
                            <?php \fpcm\view\helper::passwordInput('login[password]', '', '', false, 255, $theView->lang->translate('GLOBAL_PASSWORD')); ?>
                        <?php endif; ?>
                        </td>
                </table>

                
                <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                <?php if ($resetPasswort) : ?>
                    <?php (new \fpcm\view\helper\submitButton('reset'))->setText('GLOBAL_OK')->setClass('fpcm-loader fpcm-ok-button')->setIcon('fa fa-check fa-fw'); ?>    
                    <?php \fpcm\view\helper::linkButton($theView->basePath, 'GLOBAL_BACK', '', 'fpcm-loader fpcm-back-button'); ?>
                <?php else : ?>
                    
                    <?php (new \fpcm\view\helper\submitButton('login'))->setText('LOGIN_BTN')->setClass('fpcm-loader fpcm-login-btn')->setIcon('fa fa-sign-in fa-fw'); ?>
                    <?php \fpcm\view\helper::linkButton($theView->basePath.$theView->currentModule.'&reset', 'LOGIN_NEWPASSWORD', '', 'fpcm-loader fpcm-passreset-btn'); ?>
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