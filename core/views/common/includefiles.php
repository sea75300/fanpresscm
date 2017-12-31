<?php foreach ($FPCM_CSS_FILES as $cssFile) : ?>
<?php if (is_array($cssFile)) : ?>
    <?php print $cssFile[0]; ?><link rel="stylesheet" type="text/css" href="<?php print $cssFile[1]; ?>"><?php print $cssFile[2]; ?>
<?php else : ?>
    <link rel="stylesheet" type="text/css" href="<?php print $cssFile; ?>">
<?php endif; ?>
<?php endforeach; ?>

<?php foreach ($FPCM_JS_FILES as $jsFile) : ?>
    <script type="text/javascript" src="<?php print $jsFile; ?>"></script>
<?php endforeach; ?>