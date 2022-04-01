<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="offcanvas offcanvas-end fpcm offcanvas-large" tabindex="-1" id="offcanvasInfo" aria-labelledby="offcanvasInfoLabel" data-bs-scroll="true">
    <div class="offcanvas-header text-white bg-primary">
        <h5 class="offcanvas-title" id="offcanvasInfoLabel"><?php $theView->write($offcanvasHeadline); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php $theView->write('GLOBAL_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <div class="placeholder-glow">
            <?php include $theView->getIncludePath($offcanvasFile); ?>
        </div>
    </div>
</div>