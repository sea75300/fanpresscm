<?php /* @var $theView fpcm\view\viewVars */ ?>
<div id="fileupload" class="fileupload-processing">

    <div class="row row-cols-2">
        <div class="col-auto">
            <div class="UppyInput"></div>
        </div>
        <div class="col flex-grow-1">
            <?php $theView->button('upload')->setText('FILE_FORM_UPLOADSTART'); ?>
            <?php $theView->button('cancel')->setText('FILE_FORM_UPLOADCANCEL'); ?>
        </div>
    </div>   

    <div class="row row-cols-2">
        <div class="col-auto">
            <div class="UppyInput"></div>
        </div>
        <div class="col flex-grow-1">
            <div class="UppyInput-Progress"></div>
        </div>
    </div>    

</div>