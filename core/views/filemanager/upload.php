<?php /* @var $theView fpcm\view\viewVars */ /* @var $file fpcm\model\files\image */ ?>
<?php if (!empty($uploadFormPath)) : ?>
<div class="card mb-3" id="fileupload">
    <div class="card-body">
        
        <div class="row row-cols-2">
            <div class="col">
                <?php $theView->button('cancel')->setText('FILE_FORM_UPLOADCANCEL')->setClass('w-100')->setIcon('ban')->overrideButtonType('outline-secondary'); ?>
            </div>
            <div class="col align-self-center">
                <div id="fpcm-id-uppy-drop-area"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div id="fpcm-id-uppy-progress"></div>
                <div id="fpcm-id-uppy-informer"></div>
            </div>
        </div>
    </div>
    
    </div>
</div>
<?php endif; ?>