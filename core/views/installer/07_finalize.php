<div class="fpcm-ui-center">
    <h3><span class="fa fa-check-square"></span> <?php $FPCM_LANG->write('INSTALLER_FINALIZE'); ?></h3>
    
    <div class="fpcm-ui-center">
        <p><?php $FPCM_LANG->write('INSTALLER_FINALIZE_TEXT'); ?></p>
        <?php if ($disableInstallerMsg) : ?>
        <p class="fpcm-ui-important-text"><?php $FPCM_LANG->write('INSTALLER_FINALIZE_DIABLED'); ?></p>
        <?php endif; ?>
        
        <p><?php \fpcm\model\view\helper::linkButton('index.php', 'LOGIN_BTN'); ?></p>
    </div>
</div>