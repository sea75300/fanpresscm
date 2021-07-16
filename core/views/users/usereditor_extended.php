<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row g-0">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('USERS_AVATAR'); ?></legend>

            <?php if ($showImage) : ?>
            <div class="row g-0 mb-3">
                <div class="col-12 col-md align-self-center">
                    <div class="m-3" id="user_profile_image_buttons">
                        <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus'); ?>
                        <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('upload'); ?>
                        <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban'); ?>
                        <?php if ($avatar) : ?><?php $theView->deleteButton('fileDelete')->setClass('fpcm-ui-button-confirm'); ?><?php endif; ?>
                        <input type="file" name="files" class="fpcm-ui-fileinput-select fpcm-ui-hidden">
                    </div>

                </div>
                <div class="col-12 col-md fpcm-filelist-thumb-box align-self-center">
                <?php if ($avatar) : ?>
                    <img src="<?php print $avatar; ?>" class="img-thumbnail">
                <?php else: ?>
                    <p class="m-3"><?php $theView->icon('image')->setStack('ban text-danger')->setStackTop(true); ?>
                    <?php $theView->write('GLOBAL_NOTFOUND'); ?></p>
                <?php endif; ?>
                </div>                        
            </div>

            <?php endif; ?>
        </fieldset>
    </div>
</div>

<div class="row g-0">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('USERS_BIOGRAPHY'); ?></legend>

            <div class="row my-3">
                <div class="col-12 col-md-6">
                    <?php $theView->textarea('data[usrinfo]')
                        ->setValue($author->getUsrinfo(), ENT_QUOTES | ENT_COMPAT)
                        ->setClass('fpcm-ui-textarea-medium'); ?>                    
                </div>
            </div>
        </fieldset>
    </div>
</div>
