<?php include $theView->getIncludePath('common/includefiles.php'); ?>
<?php include $theView->getIncludePath('common/vars.php'); ?>
<?php if ($showToolbars) : ?><?php include $theView->getIncludePath('public/toolbar.php'); ?><?php endif; ?>

<?php print $article; ?>

<span id="comments"></span>
<?php print $comments; ?>

<?php if ($commentform) : ?>
    <span id="commentform"></span>
    <?php print $commentform; ?>
<?php endif; ?>

<?php if (!$hideDebug) : ?><?php fpcmDebugOutput(); ?><?php endif; ?>

<!-- Powered by FanPress CM News System version <?php print $theView->version; ?> -->