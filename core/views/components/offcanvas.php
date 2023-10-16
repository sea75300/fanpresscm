<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="offcanvas offcanvas-end fpcm offcanvas-large <?php if (!$theView->darkMode) : ?>bg-transparent<?php endif; ?>" tabindex="-1" id="offcanvasInfo" aria-labelledby="offcanvasInfoLabel" data-bs-scroll="true">
    <div class="offcanvas-header text-bg-secondary bg-opacity-75 fpcm ui-blurring">
        <h5 class="offcanvas-title" id="offcanvasInfoLabel"><?php $theView->write($offcanvasHeadline); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php $theView->write('GLOBAL_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body <?php if (!$theView->darkMode) : ?>bg-white<?php endif; ?>">
        <div class="placeholder-glow">
            <?php include $theView->getIncludePath($offcanvasFile); ?>
        </div>
    </div>
</div>