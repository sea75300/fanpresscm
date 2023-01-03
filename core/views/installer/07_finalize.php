<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="text-center">    
    <?php $theView->alert('success')->setText('INSTALLER_FINALIZE_TEXT'); ?>
    
    <?php if ($disableInstallerMsg) : ?>
        <?php $theView->alert('danger')->setText('INSTALLER_FINALIZE_DIABLED'); ?>    
    <?php endif; ?>

    <?php $theView->linkButton('toLogin')->setUrl($theView->controllerLink('system/login'))->setText('LOGIN_BTN')->setIcon('sign-in-alt'); ?>
</div>