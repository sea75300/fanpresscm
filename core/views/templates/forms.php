<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="offcanvas offcanvas-end fpcm offcanvas-large" tabindex="-1" id="offcanvasUpload" aria-labelledby="offcanvasUploadLabel" data-bs-scroll="true">
    <div class="offcanvas-header text-white bg-primary">
        <h5 class="offcanvas-title" id="offcanvasUploadLabel"><?php $theView->icon('upload'); ?> <?php $theView->write('FILE_LIST_UPLOADFORM'); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php $theView->write('GLOBAL_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <?php include $uploadTemplatePath; ?>
    </div>
</div>