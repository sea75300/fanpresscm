<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="p-3">
    <h3 class="pt-2 fs-1"><?php $theView->icon('question')->setSize('lg'); ?> <?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></h3>
    <?php print $content; ?>

    <h3 class="pt-5 fs-1"><?php $theView->icon('code-commit')->setSize('lg'); ?> <?php $theView->write('VERSION'); ?></h3>
    <p><?php print $theView->version; ?></p>

    <h3 class="pt-5 fs-1"><?php $theView->icon('copyright')->setSize('lg'); ?> <?php $theView->write('HL_HELP_LICENCE'); ?></h3>
    <?php print nl2br($theView->escapeVal($licence)); ?>

    <?php if ($backdrop) : ?>
    <p class="pt-5">
        <strong><?php $theView->icon('image')->setSize('lg'); ?> <?php $theView->write('HL_HELP_BACKDROP'); ?></strong>
        <?php $theView->linkButton('backdropCredits')->setText($backdrop)->setUrl($backdrop)->setTarget('_blank')->setRel('external')->overrideButtonType('link'); ?>
    </p>
    <?php endif; ?>

</div>