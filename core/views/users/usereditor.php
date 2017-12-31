<table class="fpcm-ui-table fpcm-ui-options">
    <tr>
        <td><?php $FPCM_LANG->write('GLOBAL_USERNAME'); ?>:</td>
        <td>
            <?php if (isset($inProfile) && $inProfile) : ?>
                <?php \fpcm\model\view\helper::textInput('username','',$author->getUserName(), true); ?>
            <?php else : ?>                
                <?php \fpcm\model\view\helper::textInput('username','',$author->getUserName()); ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td><?php $FPCM_LANG->write('GLOBAL_PASSWORD'); ?>:</td>
        <td>
            <?php \fpcm\model\view\helper::textInput('password', 'fpcm-usereditor-password') ?>
            <?php \fpcm\model\view\helper::linkButton('#', 'USERS_PASSGEN', 'generatepasswd', 'fpcm-ui-button-blank fpcm-passgen-btn'); ?>
            <?php \fpcm\model\view\helper::shortHelpButton($FPCM_LANG->translate('USERS_REQUIREMENTS')); ?>
        </td>
    </tr>
    <tr>
        <td><?php $FPCM_LANG->write('USERS_PASSWORD_CONFIRM'); ?>:</td>
        <td><?php \fpcm\model\view\helper::textInput('password_confirm'); ?></td>
    </tr>                
    <tr>
        <td><?php $FPCM_LANG->write('USERS_DISPLAYNAME'); ?>:</td>
        <td><?php \fpcm\model\view\helper::textInput('displayname','',$author->getDisplayName()); ?></td>
    </tr>
    <tr>
        <td><?php $FPCM_LANG->write('GLOBAL_EMAIL'); ?>:</td>
        <td><?php \fpcm\model\view\helper::textInput('email','',$author->getEmail()); ?></td>
    </tr>
    <tr>
        <td><?php $FPCM_LANG->write('USERS_ROLL'); ?>:</td>
        <td>
            <?php if (isset($inProfile) && $inProfile) : ?>
                <?php \fpcm\model\view\helper::select('roll', $userRolls, $author->getRoll(), false, false, true); ?>
            <?php else : ?>                
                <?php \fpcm\model\view\helper::select('roll', $userRolls, $author->getRoll(), false, false); ?>
            <?php endif; ?>                
        </td>
    </tr>
    <?php if ($showExtended) : ?>
    <tr>
        <td class="fpcm-align-top"><?php $FPCM_LANG->write('USERS_BIOGRAPHY'); ?>:</td>
        <td><?php \fpcm\model\view\helper::textArea('usrinfo','fpcm-ui-half-width fpcm-options-cssclasses',$author->getUsrinfo()); ?></td>
    </tr>
        <?php if ($showImage) : ?>
        <tr>
            <td class="fpcm-align-top"><?php $FPCM_LANG->write('USERS_AVATAR'); ?>:</td>
            <td><div class="fpcm-filemanager-buttons">
                    <?php fpcm\model\view\helper::linkButton('#', 'FILE_FORM_FILEADD', 'btnAddFile') ?>
                    <?php fpcm\model\view\helper::submitButton('uploadFile', 'FILE_FORM_UPLOADSTART', 'start-upload fpcm-loader'); ?>
                    <button type="reset" class="cancel-upload" id="btnCancelUpload"><?php $FPCM_LANG->write('FILE_FORM_UPLOADCANCEL'); ?></button>
                    <?php fpcm\model\view\helper::deleteButton('fileDelete'); ?>
                    <input type="file" name="files" class="fpcm-ui-fileinput-select fpcm-hidden">
                </div>

                <p><?php if ($avatar) : ?><img src="<?php print $avatar; ?>"><?php else: ?><?php $FPCM_LANG->write('GLOBAL_NOTFOUND'); ?><?php endif; ?></p>
            </td>
        </tr>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($showDisableButton) : ?>
    <tr>
        <td><?php $FPCM_LANG->write('GLOBAL_DISABLE'); ?>:</td>
        <td>
            <?php \fpcm\model\view\helper::boolSelect('disabled', $author->getDisabled()); ?>              
        </td>
    </tr>
    <?php endif; ?>
</table>            

<?php if (!isset($externalSave)) : ?>
<div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
    <div class="fpcm-ui-margin-center">
        <?php \fpcm\model\view\helper::saveButton('userSave'); ?>
    </div>
</div>
<?php endif; ?>

<?php \fpcm\model\view\helper::pageTokenField(); ?>