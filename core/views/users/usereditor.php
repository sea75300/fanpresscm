<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('USERS_DISPLAYNAME'); ?>:
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('displayname')->setValue($author->getDisplayName()); ?>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('GLOBAL_USERNAME'); ?>:
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('username')->setValue($author->getUserName())->setReadonly((isset($inProfile) && $inProfile)); ?>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('GLOBAL_PASSWORD'); ?>:
    </div>
    <div class="col-sm-11 col-md-6 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('password'); ?>
    </div>
    <div class="col-auto">
        <?php $theView->button('genPasswd', 'genPasswd')->setText('USERS_PASSGEN')->setIcon('key')->setIconOnly(true); ?>
        <?php $theView->shorthelpButton('dtmask')->setText('USERS_REQUIREMENTS'); ?>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('USERS_PASSWORD_CONFIRM'); ?>:
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('password_confirm'); ?>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('GLOBAL_EMAIL'); ?>:
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('email')->setValue($author->getEmail()); ?>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('USERS_ROLL'); ?>:
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-lr">
        <?php $theView->select('roll')->setOptions($userRolls)->setSelected($author->getRoll())->setReadonly((isset($inProfile) && $inProfile)); ?>
    </div>
</div>

<?php if ($showDisableButton) : ?>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('GLOBAL_DISABLE'); ?>:
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-lr">
        <?php $theView->boolSelect('disabled')->setSelected($author->getDisabled()); ?>
    </div>
</div>
<?php endif; ?>

<?php if ($showExtended) : ?>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('USERS_BIOGRAPHY'); ?>:
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-lr">
        <?php $theView->textarea('usrinfo')->setValue($author->getUsrinfo())->setClass('fpcm-ui-textarea-medium') ?>
    </div>
</div>

<?php if ($showImage) : ?>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('USERS_AVATAR'); ?>:
    </div>
    <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
        <div class="fpcm-ui-controlgroup fpcm-ui-marginbottom-lg" id="user_profile_image_buttons">
            <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus'); ?>
            <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('cloud-upload'); ?>
            <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban'); ?>
            <?php $theView->deleteButton('fileDelete')->setClass('fpcm-ui-button-confirm'); ?>
            <input type="file" name="files" class="fpcm-ui-fileinput-select fpcm-ui-hidden">
        </div>

        <p><?php if ($avatar) : ?><img src="<?php print $avatar; ?>"><?php else: ?><?php $theView->write('GLOBAL_NOTFOUND'); ?><?php endif; ?></p>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>

