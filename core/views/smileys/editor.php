<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('FILE_LIST_SMILEYCODE'); ?>
    </div>
    <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('smiley[code]')->setValue($smiley->getSmileyCode()); ?>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('FILE_LIST_FILENAME'); ?>
    </div>
    <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('smiley[filename]', 'smileyfilename')->setValue($smiley->getFilename()); ?>
    </div>
</div>