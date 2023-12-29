<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if($mode > 1) : ?><div class="d-none"><?php include_once $theView->getIncludePath('common/buttons.php'); ?></div><?php endif; ?>

<?php if (!empty($uploadFormPath)) : ?>
<div class="offcanvas offcanvas-end fpcm offcanvas-large" tabindex="-1" id="offcanvasUpload" aria-labelledby="offcanvasUploadLabel" data-bs-scroll="true">
    <div class="offcanvas-header text-white bg-primary">
        <h5 class="offcanvas-title" id="offcanvasUploadLabel"><?php $theView->icon('upload'); ?> <?php $theView->write('FILE_LIST_UPLOADFORM'); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php $theView->write('GLOBAL_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <?php include $uploadFormPath; ?>
    </div>
</div>
<?php endif; ?>