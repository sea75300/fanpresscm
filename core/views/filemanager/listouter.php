<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div id="tabs-files-list">
    <div id="tabs-files-list-content">
        <?php if (!$hasFiles) : ?>
        <p class="p-3"><?php $theView->icon('images', 'far')->setStack('ban text-danger')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
        <?php else : ?>
        <div class="row g-0 align-self-center fpcm-ui-inline-loader">
            <div class="col-12 fpcm-ui-center align-self-center">
                <?php $theView->icon('spinner fa-inverse')->setSpinner('pulse')->setStack('circle')->setSize('2x'); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>