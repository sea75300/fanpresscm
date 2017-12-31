<?php include_once dirname(__DIR__).'/common/includefiles.php'; ?>
<?php include_once dirname(__DIR__).'/common/vars.php'; ?>

<?php if ($showToolbars) : ?><?php include __DIR__.'/toolbar.php'; ?><?php endif; ?>

<?php print $article; ?>

<span id="comments"></span>
<?php print $comments; ?>

<?php if ($commentform) : ?>
    <span id="commentform"></span>
    <?php print $commentform; ?>
<?php endif; ?>

<?php if (!$hideDebug) : ?><?php fpcmDebugOutput(); ?><?php endif; ?>

<!-- Powered by FanPress CM News System version <?php print $FPCM_VERSION; ?> -->