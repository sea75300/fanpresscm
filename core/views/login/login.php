<div class="fpcm-content-wrapper">
    <div class="fpcm-login-form">
    <?php if (!isset($lockedGlobal)) : ?>
        <?php if ($loginAttempts < $loginAttemptsMax) : ?>
        <div class="ui-widget-content ui-corner-all ui-state-normal">
            <form method="post" action="<?php print $FPCM_BASEMODULELINK; ?>system/login">
                <table class="fpcm-ui-table fpcm-login-form-table">
                    <tr>
                        <td>
                        <?php if ($resetPasswort) : ?>
                            <?php \fpcm\model\view\helper::textInput('username', '', '', false, 255, $FPCM_LANG->translate('GLOBAL_USERNAME')); ?>
                        <?php else : ?>
                            <?php \fpcm\model\view\helper::textInput('login[username]', '', '', false, 255, $FPCM_LANG->translate('GLOBAL_USERNAME')); ?>
                        <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <?php if ($resetPasswort) : ?>
                            <?php \fpcm\model\view\helper::textInput('email', '', '', false, 255, $FPCM_LANG->translate('GLOBAL_EMAIL')); ?>
                        <?php else : ?>
                            <?php \fpcm\model\view\helper::passwordInput('login[password]', '', '', false, 255, $FPCM_LANG->translate('GLOBAL_PASSWORD')); ?>
                        <?php endif; ?>
                        </td>
                </table>

                
                <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                <?php if ($resetPasswort) : ?>
                    <?php \fpcm\model\view\helper::submitButton('reset', 'GLOBAL_OK', 'fpcm-loader fpcm-ok-button'); ?>
                    <?php \fpcm\model\view\helper::linkButton($FPCM_BASELINK, 'GLOBAL_BACK', '', 'fpcm-loader fpcm-back-button'); ?>
                <?php else : ?>
                    <?php \fpcm\model\view\helper::submitButton('login', 'LOGIN_BTN', 'fpcm-loader fpcm-login-btn'); ?>
                    <?php \fpcm\model\view\helper::linkButton($FPCM_BASELINK.'index.php?module='.$FPCM_CURRENT_MODULE.'&reset', 'LOGIN_NEWPASSWORD', '', 'fpcm-loader fpcm-passreset-btn'); ?>
                <?php endif; ?>
                    </div>
                </div>
                
                <?php \fpcm\model\view\helper::pageTokenField(); ?>
                
            </form> 
        </div>
        <?php endif; ?>    
    <?php endif; ?>
    </div>
</div>