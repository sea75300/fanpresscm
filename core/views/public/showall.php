<?php include_once dirname(__DIR__).'/common/includefiles.php'; ?>
<?php include_once dirname(__DIR__).'/common/vars.php'; ?>

<?php if ($showToolbars) : ?><?php include __DIR__.'/toolbar.php'; ?><?php endif; ?>

<?php print $content; ?>

<?php if (!$hideDebug) : ?><?php fpcmDebugOutput(); ?><?php endif; ?>

<!-- Powered by FanPress CM News System version <?php print $FPCM_VERSION; ?> -->