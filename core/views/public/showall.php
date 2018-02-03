<?php include $theView->getIncludePath('common/includefiles.php'); ?>
<?php include $theView->getIncludePath('common/vars.php'); ?>
<?php if ($showToolbars) : ?><?php include $theView->getIncludePath('public/toolbar.php'); ?><?php endif; ?>

<?php print $content; ?>

<?php if (!$hideDebug) : ?><?php fpcmDebugOutput(); ?><?php endif; ?>

<!-- Powered by FanPress CM News System version <?php print $theView->version; ?> -->