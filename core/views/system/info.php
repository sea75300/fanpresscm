<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="p-3">
    <h3 class="pt-2 fs-1"><?php $theView->icon('question')->setSize('lg'); ?> <?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></h3>
    <?php print $content; ?>

    <h3 class="pt-5 fs-1"><?php $theView->icon('code-commit')->setSize('lg'); ?> <?php $theView->write('VERSION'); ?></h3>
    <p><?php print $theView->version; ?></p>

    <h3 class="pt-5 fs-1"><?php $theView->icon('copyright')->setSize('lg'); ?> <?php $theView->write('HL_HELP_LICENCE'); ?></h3>
    <?php print nl2br($theView->escapeVal($licence)); ?>

    <p class="pt-5">
        <strong><?php $theView->icon('image')->setSize('lg'); ?> <?php $theView->write('HL_HELP_BACKDROP'); ?></strong>
        <a class="link-secondary" href="https://www.pexels.com/de-de/foto/klares-blaues-ufer-457881/" rel="external" target="_blank">https://www.pexels.com/de-de/foto/klares-blaues-ufer-457881/</a>.
    </p>

</div>