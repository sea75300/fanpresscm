<?php /* @var $theView fpcm\view\viewVars */ ?>
<p class="col-8 text-center">
    <p><?php $theView->write('INSTALLER_FINALIZE_TEXT'); ?></p>
    <?php if ($disableInstallerMsg) : ?>
    <p class="text-danger"><?php $theView->write('INSTALLER_FINALIZE_DIABLED'); ?></p>
    <?php endif; ?>

    <?php $theView->linkButton('toLogin')->setUrl($theView->controllerLink('system/login'))->setText('LOGIN_BTN')->setIcon('sign-in-alt'); ?>
</p>