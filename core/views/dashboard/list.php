<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php foreach ($containers as $container) : ?>
    <?php print $container; ?>
<?php endforeach; ?>    

<script>jQuery.extend(fpcm, <?php print json_encode($theView->varsJs, JSON_PARTIAL_OUTPUT_ON_ERROR); ?>);</script>
<?php foreach ($jsFiles as $jsFile) : ?><?php if (!trim($jsFile)) continue; ?><script src="<?php print $jsFile; ?>"></script><?php endforeach; ?>