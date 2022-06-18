<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="offcanvas offcanvas-end fpcm offcanvas-large bg-transparent" tabindex="-1" id="offcanvasInfo" aria-labelledby="offcanvasInfoLabel" data-bs-scroll="true">
    <div class="offcanvas-header text-white fpcm ui-background-blue-75 ui-blurring">
        <h5 class="offcanvas-title" id="offcanvasInfoLabel"><?php $theView->write($offcanvasHeadline); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php $theView->write('GLOBAL_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body bg-white">
        <div class="placeholder-glow">
            <?php include $theView->getIncludePath($offcanvasFile); ?>
        </div>
    </div>
</div>