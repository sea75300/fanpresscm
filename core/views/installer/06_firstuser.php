<div class="fpcm-ui-center">
    <h3><span class="fa fa-user-plus"></span> <?php $FPCM_LANG->write('INSTALLER_ADMINUSER'); ?></h3>
    
    <div class="fpcm-ui-left">
        <?php $showDisableButton = false; ?>
        <?php $showExtended      = false; ?>
        <?php $showImage         = false; ?>
        <?php $avatar            = false; ?>
        <?php include_once dirname(__DIR__).'/users/usereditor.php'; ?>        
    </div>
</div>