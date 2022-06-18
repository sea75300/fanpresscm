<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php include $theView->getIncludePath('components/dataview__inline.php'); ?>

<?php include $theView->getIncludePath('comments/searchform.php'); ?>
<?php if ($theView->permissions->editCommentsMass()) : ?><?php include $theView->getIncludePath('comments/massedit.php'); ?><?php endif; ?>

