<div class="row fpcm-ui-padding-md-tb no-gutters">
    <div class="col-12">
        <div class="row">
            <?php $theView->textInput('smiley[code]')
                ->setValue($smiley->getSmileyCode())
                ->setWrapper(false)
                ->setText('FILE_LIST_SMILEYCODE')
                ->setIcon('bookmark')
                ->setClass('col-12 col-md-10 fpcm-ui-field-input-nowrapper-general')
                ->setLabelClass('col-12 col-md-2 fpcm-ui-field-label-general'); ?>
        </div>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb no-gutters">
    <div class="col-12">
        <div class="row">
            <?php $theView->textInput('smiley[filename]', 'smileyfilename')
                ->setValue($smiley->getFilename())
                ->setWrapper(false)
                ->setText('FILE_LIST_FILENAME')
                ->setIcon('link')
                ->setClass('col-12 col-md-10 fpcm-ui-field-input-nowrapper-general')
                ->setLabelClass('col-12 col-md-2 fpcm-ui-field-label-general'); ?>
        </div>
    </div>
</div>