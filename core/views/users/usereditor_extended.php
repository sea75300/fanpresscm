<?php /* @var $theView fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('USERS_AVATAR'); ?></legend>

    <?php if ($showImage) : ?>
    
    <div class="row row-cols-1 row-cols-sm-2">
        
        <div class="col col-lg-2">
            
            <div class="card-group">

                <div class="card rounded">

                    <?php if ($avatar) : ?>
                        <img id="fpcm-ui-avatar" class="img-thumbnail" loading="lazy" src="<?php print $avatar; ?>" title="<?php $theView->write('USERS_AVATAR'); ?>">
                    <?php else: ?>
                        <img id="fpcm-ui-avatar" class="card-img-top rounded-top overflow-hidden p-5" loading="lazy" src="<?php print fpcm\classes\loader::libGetFileUrl('font-awesome/svg/image.svg'); ?>" title="<?php $theView->write('USERS_AVATAR'); ?>">
                    <?php endif; ?>

                    <?php if ($avatar) : ?>
                    <div class="card-footer bg-transparent">
                        <?php $theView->deleteButton('fileDelete')->setClass('fpcm ui-button-confirm'); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col col-lg-4">
            <?php if (!empty($uploadTemplatePath)) : ?>
                <?php include $uploadTemplatePath; ?>
            <?php else : ?>
            
            <div class="rounded-2 p-2 bg-white shadow-sm">
                <div class="btn-group-vertical back w-100" role="group">
                    <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus')->setPrimary()->setData(['click-trigger' => 'avatar-upload-files']); ?>
                    <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('upload')->overrideButtonType('outline-secondary'); ?>
                    <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban')->overrideButtonType('outline-secondary'); ?>
                </div>            
                <input type="file" name="files" class="fpcm-ui-fileinput-select d-none invisible" id="fpcm-id-avatar-upload-files">
            </div>

            <div class="list-group my-2" id="fpcm-id-upload-list" role="presentation"></div>
            <?php endif; ?>
        </div>
        
    </div>
    

    <?php endif; ?>
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('USERS_BIOGRAPHY'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-6">
            <?php $theView->textarea('data[usrinfo]')
                ->setValue($author->getUsrinfo(), ENT_QUOTES | ENT_COMPAT)
                ->setClass('fpcm-ui-textarea-medium'); ?>                    
        </div>
    </div>
</fieldset>