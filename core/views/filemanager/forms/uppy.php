<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row row-cols-3 g-1">
    <div class="col-auto">
        <div id="fpcm-uppy-select"></div>
    </div>
    <div class="col-auto">
        <?php $theView->button('upload')->setText('FILE_FORM_UPLOADSTART'); ?>
    </div>
    <div class="col flex-grow-1">
        <?php $theView->button('cancel')->setText('FILE_FORM_UPLOADCANCEL'); ?>
    </div>
</div>

<div class="row align-self-center mb-3">        
    <div id="fpcm-uppy-drop-area"></div>
</div>

<div class="row row-cols-2">
    <div class="col flex-grow-1">
        <div id="fpcm-uppy-progress"></div>
    </div>
</div>    
