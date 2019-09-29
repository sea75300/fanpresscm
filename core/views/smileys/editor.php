<div class="row fpcm-ui-padding-md-tb no-gutters">
    <div class="col-12">
        <div class="row">
            <?php $theView->textInput('smiley[code]')
                ->setValue($smiley->getSmileyCode())
                ->setWrapper(false)
                ->setText('FILE_LIST_SMILEYCODE')
                ->setIcon('bookmark')
                ->setDisplaySizes(['xs' => 12, 'md' => 2], ['xs' => 12, 'md' => 10]); ?>
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
                ->setDisplaySizes(['xs' => 12, 'md' => 2], ['xs' => 12, 'md' => 10]); ?>
        </div>
    </div>
</div>