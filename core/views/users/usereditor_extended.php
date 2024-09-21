<?php /* @var $theView fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('USERS_AVATAR'); ?></legend>

    <?php if ($showImage) : ?>
    
    <div class="row row-cols-1 row-cols-sm-2">
        
        <div class="col col-lg-2">
            

                <div class="card rounded">
                    
                    <img id="fpcm-ui-avatar"
                        class="card-img-top rounded-top <?php if (!$avatar) : ?> overflow-hidden p-4<?php endif; ?>"
                        loading="lazy"
                        title="<?php $theView->write('USERS_AVATAR'); ?>"
                        src="
                        <?php if ($avatar) : ?>
                            <?php print $avatar; ?>
                        <?php else: ?>
                            <?php print fpcm\classes\loader::libGetFileUrl('font-awesome/svg/image.svg'); ?>
                        <?php endif; ?>">

                    <div class="card-footer bg-transparent d-flex justify-content-end">
                    <?php $theView->deleteButton('fileDelete')->setClickConfirm()->setReadonly(!$avatar)->overrideButtonType('outline-secondary'); ?>
                    </div>
                </div>
        </div>
        
        <div class="col col-lg-4">
            <?php include $uploadTemplatePath; ?>
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
                ->setClass('fpcm ui-textarea-medium')
                ->setText('USERS_BIOGRAPHY')
                ->setLabelTypeFloat(); ?>
        </div>
    </div>
</fieldset>
