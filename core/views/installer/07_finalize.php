<div class="fpcm-ui-center">
    <h3><span class="fa fa-check-square"></span> <?php $theView->write('INSTALLER_FINALIZE'); ?></h3>
    
    <div class="fpcm-ui-center">
        <p><?php $theView->write('INSTALLER_FINALIZE_TEXT'); ?></p>
        <?php if ($disableInstallerMsg) : ?>
        <p class="fpcm-ui-important-text"><?php $theView->write('INSTALLER_FINALIZE_DIABLED'); ?></p>
        <?php endif; ?>
        
        <p><?php \fpcm\view\helper::linkButton('index.php', 'LOGIN_BTN'); ?></p>
    </div>
</div>