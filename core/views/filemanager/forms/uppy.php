<?php /* @var $theView fpcm\view\viewVars */ ?>
<div id="fileupload">

    <div class="row my-3 <?php if (isset($hideDropArea)) : ?>d-none<?php endif; ?>">
        <div class="col align-self-center">
            <div id="fpcm-id-uppy-drop-area"></div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div id="fpcm-id-uppy-select" class="<?php if (!isset($hideDropArea)) : ?>d-none mb-3<?php else : ?>my-3<?php endif; ?>"></div>
            
            <div class="btn-group btn-group-sm w-100 mb-3" role="group" aria-label="<?php $theView->write('GLOBAL_ACTIONS') ?>">
                <?php $theView->button('pause')
                        ->setText('FILE_FORM_UPLOADPAUSE')
                        ->setIcon('pause')
                        ->overrideButtonType('outline-secondary')
                        ->setReadonly()
                        ->setLabelClass('d-none d-lg-inline-block');
                ?>

                <?php $theView->button('resume')
                        ->setText('FILE_FORM_UPLOADRESUME')
                        ->setIcon('play')
                        ->overrideButtonType('outline-secondary')
                        ->setReadonly()
                        ->setLabelClass('d-none d-lg-inline-block');
                ?>

                <?php $theView->button('cancel')
                        ->setText('FILE_FORM_UPLOADCANCEL')
                        ->setIcon('ban')
                        ->overrideButtonType('outline-secondary')
                        ->setLabelClass('d-none d-lg-inline-block');
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div id="fpcm-id-uppy-progress"></div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div id="fpcm-id-uppy-informer"></div>
        </div>
    </div>
</div>