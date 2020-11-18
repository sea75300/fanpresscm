<?php foreach ($theView->filesJsLate as $jsFile) : ?>
    <?php if (!trim($jsFile)) continue; ?>
    <script src="<?php print $jsFile; ?>" rel="prefetch"></script>
<?php endforeach; ?>
<?php unset($jsFile); ?>
