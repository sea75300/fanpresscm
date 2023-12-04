<?php /* @var $theView fpcm\view\viewVars */ ?>
<div id="fileupload">
    <div class="row">
        <div class="col">
            <div id="fpcm-id-uppy-select" class="mb-3"></div>
            <?php $theView->button('cancel')->setText('FILE_FORM_UPLOADCANCEL')->setClass('w-100')->setIcon('ban')->overrideButtonType('outline-secondary'); ?>
        </div>
    </div>

    <div class="row my-3 <?php if (isset($hideDropArea)) : ?>d-none<?php endif; ?>">
        <div class="col align-self-center">
            <div id="fpcm-id-uppy-drop-area"></div>
        </div>
    </div>

    <div class="row my-3 g-0">
        <div class="col-12">
            <div id="fpcm-id-uppy-progress"></div>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-12">
            <div id="fpcm-id-uppy-informer"></div>
        </div>
    </div>
</div>