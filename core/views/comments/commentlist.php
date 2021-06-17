<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div id="fpcm-dataview-commentlist"></div>

<?php include $theView->getIncludePath('comments/searchform.php'); ?>
<?php if ($theView->permissions->editCommentsMass()) : ?><?php include $theView->getIncludePath('comments/massedit.php'); ?><?php endif; ?>

