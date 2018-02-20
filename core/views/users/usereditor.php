<table class="fpcm-ui-table fpcm-ui-options">
    <tr>
        <td><?php $theView->write('GLOBAL_USERNAME'); ?>:</td>
        <td>
            <?php if (isset($inProfile) && $inProfile) : ?>
                <?php \fpcm\view\helper::textInput('username','',$author->getUserName(), true); ?>
            <?php else : ?>                
                <?php \fpcm\view\helper::textInput('username','',$author->getUserName()); ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td><?php $theView->write('GLOBAL_PASSWORD'); ?>:</td>
        <td>
            <?php \fpcm\view\helper::textInput('password', 'fpcm-usereditor-password') ?>
            <?php \fpcm\view\helper::linkButton('#', 'USERS_PASSGEN', 'generatepasswd', 'fpcm-ui-button-blank fpcm-passgen-btn'); ?>
            <?php $theView->shorthelpButton('dtmask')->setText('USERS_REQUIREMENTS'); ?>
        </td>
    </tr>
    <tr>
        <td><?php $theView->write('USERS_PASSWORD_CONFIRM'); ?>:</td>
        <td><?php \fpcm\view\helper::textInput('password_confirm'); ?></td>
    </tr>                
    <tr>
        <td><?php $theView->write('USERS_DISPLAYNAME'); ?>:</td>
        <td><?php \fpcm\view\helper::textInput('displayname','',$author->getDisplayName()); ?></td>
    </tr>
    <tr>
        <td><?php $theView->write('GLOBAL_EMAIL'); ?>:</td>
        <td><?php \fpcm\view\helper::textInput('email','',$author->getEmail()); ?></td>
    </tr>
    <tr>
        <td><?php $theView->write('USERS_ROLL'); ?>:</td>
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
        <td class="fpcm-align-top"><?php $theView->write('USERS_BIOGRAPHY'); ?>:</td>
        <td><?php \fpcm\view\helper::textArea('usrinfo','fpcm-ui-half-width fpcm-ui-textarea-medium',$author->getUsrinfo()); ?></td>
    </tr>
        <?php if ($showImage) : ?>
        <tr>
            <td class="fpcm-align-top"><?php $theView->write('USERS_AVATAR'); ?>:</td>
            <td>
                
                <div class="fpcm-ui-controlgroup fpcm-ui-marginbottom-lg" id="user_profile_image_buttons">
                    <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus'); ?>
                    <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('cloud-upload'); ?>
                    <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban'); ?>
                    <?php $theView->deleteButton('fileDelete')->setClass('fpcm-ui-button-confirm'); ?>
                    <input type="file" name="files" class="fpcm-ui-fileinput-select fpcm-ui-hidden">
                </div>

                <p><?php if ($avatar) : ?><img src="<?php print $avatar; ?>"><?php else: ?><?php $theView->write('GLOBAL_NOTFOUND'); ?><?php endif; ?></p>
            </td>
        </tr>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($showDisableButton) : ?>
    <tr>
        <td><?php $theView->write('GLOBAL_DISABLE'); ?>:</td>
        <td>
            <?php $theView->boolSelect('disabled')->setSelected($author->getDisabled()); ?>              
        </td>
    </tr>
    <?php endif; ?>
</table>

<?php $theView->pageTokenField('pgtkn'); ?>