<?php foreach ($theView->filesCss as $cssFile) : ?>
<?php if (is_array($cssFile)) : ?>
    <?php print $cssFile[0]; ?><link rel="stylesheet prefetch" type="text/css" href="<?php print $cssFile[1]; ?>"><?php print $cssFile[2]; ?>
<?php else : ?>
    <link rel="stylesheet prefetch" type="text/css" href="<?php print $cssFile; ?>">
<?php endif; ?>
<?php endforeach; ?>
<?php unset($cssFile); ?>

<?php foreach ($theView->filesJs as $jsFile) : ?>
    <?php if (!trim($jsFile)) continue; ?>
    <script src="<?php print $jsFile; ?>" rel="prefetch"></script>
<?php endforeach; ?>
<?php unset($jsFile); ?>