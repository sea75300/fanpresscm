<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php include_once $theView->getIncludePath('common/buttons.php'); ?>

<?php include $theView->getIncludePath('users/permissions_editor.php'); ?>

<?php $theView->saveButton('permissionsSave')->setClass('fpcm ui-hidden') ?>