<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters">
    <div class="col-12">
        <fieldset class="py-2">
            <legend><?php $theView->write($tabActionString); ?></legend>

            <div class="row py-2">
                <?php $theView->textInput('smiley[code]')
                    ->setValue($smiley->getSmileyCode())
                    ->setText('FILE_LIST_SMILEYCODE')
                    ->setIcon('bookmark')
                    ->setAutoFocused(true); ?>
            </div>

            <div class="row py-2">
                <?php $theView->textInput('smiley[filename]', 'smileyfilename')
                    ->setValue($smiley->getFilename())
                    ->setText('FILE_LIST_FILENAME')
                    ->setIcon('link'); ?>
            </div>                        
        </fieldset>
    </div>
</div>