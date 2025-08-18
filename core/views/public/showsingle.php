<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($systemMode == 1) : ?>
    <?php include $theView->getIncludePath('/common/includefiles.php'); ?>
    <?php include $theView->getIncludePath('/common/vars.php'); ?>
    <div id="fpcm-messages"></div>
<?php endif; ?>
<?php include $theView->getIncludePath('public/toolbar.php'); ?>

<?php print $article; ?>

<div id="fpcm-pub-comments">
<?php print $comments; ?>    
</div>

<?php if ($commentform) : ?>
<div id="commentform">
    <?php print $commentform; ?>

</div>
<?php endif; ?>

<?php if (!$hideDebug) : ?><?php fpcmDebugOutput(); ?><?php endif; ?>

<!-- Powered by FanPress CM News System version <?php print $theView->version; ?> -->