<?php foreach ($theView->filesJsBtm as $jsFile) : ?>
    <?php if (!trim($jsFile)) continue; ?>
    <script src="<?php print $jsFile; ?>" rel="prefetch"></script>
<?php endforeach; ?>