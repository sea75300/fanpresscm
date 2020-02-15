<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($systemMode == 1) : ?>
    <?php include $theView->getIncludePath('/common/includefiles.php'); ?>
    <?php include $theView->getIncludePath('/common/vars.php'); ?>
<?php endif; ?>
<?php include $theView->getIncludePath('public/toolbar.php'); ?>

<?php print $content; ?>

<?php if (!$hideDebug) : ?><?php fpcmDebugOutput(); ?><?php endif; ?>

<!-- Powered by FanPress CM News System version <?php print $theView->version; ?> -->