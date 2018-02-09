<table class="fpcm-ui-table fpcm-ui-options">
    <tr>
        <td><?php $theView->lang->write('GLOBAL_USERNAME'); ?>:</td>
        <td>
            <?php if (isset($inProfile) && $inProfile) : ?>
                <?php \fpcm\view\helper::textInput('username','',$author->getUserName(), true); ?>
            <?php else : ?>                
                <?php \fpcm\view\helper::textInput('username','',$author->getUserName()); ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td><?php $theView->lang->write('GLOBAL_PASSWORD'); ?>:</td>
        <td>
            <?php \fpcm\view\helper::textInput('password', 'fpcm-usereditor-password') ?>
            <?php \fpcm\view\helper::linkButton('#', 'USERS_PASSGEN', 'generatepasswd', 'fpcm-ui-button-blank fpcm-passgen-btn'); ?>
            <?php $theView->shorthelpButton('dtmask')->setText('USERS_REQUIREMENTS'); ?>
        </td>
    </tr>
    <tr>
        <td><?php $theView->lang->write('USERS_PASSWORD_CONFIRM'); ?>:</td>
        <td><?php \fpcm\view\helper::textInput('password_confirm'); ?></td>
    </tr>                
    <tr>
        <td><?php $theView->lang->write('USERS_DISPLAYNAME'); ?>:</td>
        <td><?php \fpcm\view\helper::textInput('displayname','',$author->getDisplayName()); ?></td>
    </tr>
    <tr>
        <td><?php $theView->lang->write('GLOBAL_EMAIL'); ?>:</td>
        <td><?php \fpcm\view\helper::textInput('email','',$author->getEmail()); ?></td>
    </tr>
    <tr>
        <td><?php $theView->lang->write('USERS_ROLL'); ?>:</td>
        <td>
            <?php if (isset($inProfile) && $inProfile) : ?>
                <?php \fpcm\view\helper::select('roll', $userRolls, $author->getRoll(), false, false, true); ?>
            <?php else : ?>                
                <?php \fpcm\view\helper::select('roll', $userRolls, $author->getRoll(), false, false); ?>
            <?php endif; ?>                
        </td>
    </tr>
    <?php if ($showExtended) : ?>
    <tr>
        <td class="fpcm-align-top"><?php $theView->lang->write('USERS_BIOGRAPHY'); ?>:</td>
        <td><?php \fpcm\view\helper::textArea('usrinfo','fpcm-ui-half-width fpcm-ui-textarea-medium',$author->getUsrinfo()); ?></td>
    </tr>
        <?php if ($showImage) : ?>
        <tr>
            <td class="fpcm-align-top"><?php $theView->lang->write('USERS_AVATAR'); ?>:</td>
            <td><div class="fpcm-filemanager-buttons">
                    <?php fpcm\view\helper::linkButton('#', 'FILE_FORM_FILEADD', 'btnAddFile') ?>
                    <?php fpcm\view\helper::submitButton('uploadFile', 'FILE_FORM_UPLOADSTART', 'start-upload fpcm-loader'); ?>
                    <button type="reset" class="cancel-upload" id="btnCancelUpload"><?php $theView->lang->write('FILE_FORM_UPLOADCANCEL'); ?></button>
                    <?php fpcm\view\helper::deleteButton('fileDelete'); ?>
                    <input type="file" name="files" class="fpcm-ui-fileinput-select fpcm-ui-hidden">
                </div>

                <p><?php if ($avatar) : ?><img src="<?php print $avatar; ?>"><?php else: ?><?php $theView->lang->write('GLOBAL_NOTFOUND'); ?><?php endif; ?></p>
            </td>
        </tr>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($showDisableButton) : ?>
    <tr>
        <td><?php $theView->lang->write('GLOBAL_DISABLE'); ?>:</td>
        <td>
            <?php \fpcm\view\helper::boolSelect('disabled', $author->getDisabled()); ?>              
        </td>
    </tr>
    <?php endif; ?>
</table>

<?php $theView->pageTokenField('pgtkn'); ?>