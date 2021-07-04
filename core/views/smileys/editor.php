<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row">
    <div class="col-12">
        <fieldset class="my-3">

            <div class="row">
                <?php $theView->textInput('smiley[code]')
                    ->setValue($smiley->getSmileyCode())
                    ->setText('FILE_LIST_SMILEYCODE')
                    ->setIcon('bookmark')
                    ->setAutoFocused(true); ?>
            </div>

            <div class="row">
                <?php $theView->textInput('smiley[filename]', 'smileyfilename')
                    ->setValue($smiley->getFilename())
                    ->setText('FILE_LIST_FILENAME')
                    ->setIcon('link'); ?>
            </div>                        
        </fieldset>
    </div>
</div>