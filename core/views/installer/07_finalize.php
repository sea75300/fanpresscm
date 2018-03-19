<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row align-items-center">

    <div class="col-12">
        <h3><span class="fa fa-check-square"></span> <?php $theView->write('INSTALLER_FINALIZE'); ?></h3>
    </div>

    <div class="col-12 col-md-6 fpcm-ui-center">
        
        <p><?php $theView->write('INSTALLER_FINALIZE_TEXT'); ?></p>
        <?php if ($disableInstallerMsg) : ?>
        <p class="fpcm-ui-important-text"><?php $theView->write('INSTALLER_FINALIZE_DIABLED'); ?></p>
        <?php endif; ?>
        
        <?php $theView->linkButton('toLogin')->setUrl(fpcm\classes\tools::getFullControllerLink('system/login'))->setText('LOGIN_BTN'); ?>
    </div>

</div>