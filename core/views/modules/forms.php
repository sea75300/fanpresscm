<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="offcanvas offcanvas-end fpcm offcanvas-large" tabindex="-1" id="offcanvasInfo" aria-labelledby="offcanvasInfoLabel" data-bs-scroll="true">
    <div class="offcanvas-header text-white bg-primary">
        <h5 class="offcanvas-title" id="offcanvasInfoLabel"><?php $theView->icon('plug'); ?> <?php $theView->write('MODULES_LIST_INFORMATIONS'); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php $theView->write('GLOBAL_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <div class="placeholder-glow">
            <div class="row justify-content-end mb-3">
                <div class="col-12 col-md-auto fw-bold fs-3 text-start flex-grow-1 align-self-center text-truncate">
                    <div class="d-inline-block placeholder w-100">&nbsp;</div>
                </div>
                <div class="col-auto col-md-auto align-self-center">
                    <span class="placeholder"><?php $theView->icon('plus-circle'); ?></span>
                </div>
                <div class="col-auto col-md-auto align-self-center">
                    <span class="placeholder"><?php $theView->icon('cloud-download-alt'); ?></span>
                </div>
                <div class="col-auto col-md-auto align-self-center">
                    <span class="placeholder"><?php $theView->icon('external-link-square-alt'); ?></span>
                </div>
            </div>             

            <div class="row row-cols-2 placeholder-wave mb-2">
                <div class="col">
                    <span class="placeholder w-50"></span>
                </div>
                <div class="col">
                    <span class="placeholder w-100"></span>
                </div>
            </div>
            <div class="row row-cols-2 placeholder-wave mb-2">
                <div class="col">
                    <span class="placeholder w-50"></span>
                </div>
                <div class="col">
                    <span class="placeholder w-25"></span>
                </div>
            </div>
            <div class="row row-cols-2 placeholder-wave mb-2">
                <div class="col">
                    <span class="placeholder w-75"></span>
                </div>
                <div class="col">
                    <span class="placeholder w-50"></span>
                </div>
            </div>
            <div class="row row-cols-2 placeholder-wave mb-2">
                <div class="col">
                    <span class="placeholder w-100"></span>
                </div>
                <div class="col">
                    <span class="placeholder w-100"></span>
                </div>
            </div>
        </div>
    </div>
</div>