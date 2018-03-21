<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="col-12 col-sm-8 fpcm-ui-center">

    <p><?php $theView->write('INSTALLER_FINALIZE_TEXT'); ?></p>
    <?php if ($disableInstallerMsg) : ?>
    <p class="fpcm-ui-important-text"><?php $theView->write('INSTALLER_FINALIZE_DIABLED'); ?></p>
    <?php endif; ?>

    <?php $theView->linkButton('toLogin')->setUrl(fpcm\classes\tools::getFullControllerLink('system/login'))->setText('LOGIN_BTN')->setIcon('sign-in'); ?>
</div>