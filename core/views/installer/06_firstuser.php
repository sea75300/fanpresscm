<div class="fpcm-ui-center">
    <h3><span class="fa fa-user-plus"></span> <?php $theView->write('INSTALLER_ADMINUSER'); ?></h3>
    
    <div class="fpcm-ui-left">
        <?php $showDisableButton = false; ?>
        <?php $showExtended      = false; ?>
        <?php $showImage         = false; ?>
        <?php $avatar            = false; ?>
        <?php include $theView->getIncludePath('users/usereditor.php'); ?>
    </div>
</div>