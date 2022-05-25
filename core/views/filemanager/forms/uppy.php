<?php /* @var $theView fpcm\view\viewVars */ ?>
<div id="fileupload">
    <div class="row row-cols-1 row-cols-md-2">
        <div class="col align-self-center mb-2">
            <div id="fpcm-uppy-select"></div>
            <?php $theView->button('upload')->setText('FILE_FORM_UPLOADSTART')->setClass('w-100 mb-2')->setIcon('upload')->overrideButtonType('outline-secondary'); ?>
            <?php $theView->button('cancel')->setText('FILE_FORM_UPLOADCANCEL')->setClass('w-100')->setIcon('ban')->overrideButtonType('outline-secondary'); ?>
        </div>
        <div class="col align-self-center">
            <div class="col-12 shadow d-none d-lg-block">
                <div id="fpcm-uppy-drop-area"></div>
            </div>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-12">
            <div id="fpcm-uppy-progress"></div>
        </div>
    </div>
</div>