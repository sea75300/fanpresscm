<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tab-smiley"><?php $theView->write('FILE_LIST_SMILEY'.$tabAction); ?></a></li>
        </ul>            

        <div id="tab-smiley">
            <?php /* @var $theView \fpcm\view\viewVars */ ?>
            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12">
                    <div class="row">
                        <?php $theView->textInput('smiley[code]')
                            ->setValue($smiley->getSmileyCode())
                            ->setText('FILE_LIST_SMILEYCODE')
                            ->setIcon('bookmark')
                            ->setAutoFocused(true)
                            ->setDisplaySizesDefault(); ?>
                    </div>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12">
                    <div class="row">
                        <?php $theView->textInput('smiley[filename]', 'smileyfilename')
                            ->setValue($smiley->getFilename())
                            ->setText('FILE_LIST_FILENAME')
                            ->setIcon('link')
                            ->setDisplaySizesDefault(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>