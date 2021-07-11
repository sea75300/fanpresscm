<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div id="tabs-files-list">
    <div id="tabs-files-list-content">
        <?php if (!$hasFiles) : ?>
        <p class="fpcm-ui-padding-none fpcm-ui-margin-none"><?php $theView->icon('images', 'far')->setStack('ban fpcm-ui-important-text')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
        <?php else : ?>
        <div class="row g-0 align-self-center fpcm-ui-inline-loader">
            <div class="col-12 fpcm-ui-center align-self-center">
                <?php $theView->icon('spinner fa-inverse')->setSpinner('pulse')->setStack('circle')->setSize('2x'); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>