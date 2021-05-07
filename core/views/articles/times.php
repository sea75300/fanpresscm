<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row g-0 fpcm-ui-editor-metabox fpcm-ui-padding-md-top">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('GLOBAL_METADATA'); ?></legend>

            <div class="row g-0">
                <div class="<?php $theView->defaultBoxHalf(); ?> align-self-center">

                    <div class="fpcm-ui-editor-metabox-left">
                        <div class="col-11 ps-0 fpcm-ui-ellipsis">
                            <?php $theView->icon('calendar'); ?> <?php print $createInfo; ?><br>
                            <?php $theView->icon('clock', 'far'); ?> <?php print $changeInfo; ?>
                        </div>
                    </div>
                </div>

                <div class="<?php $theView->defaultBoxHalf(); ?> fpcm-ui-align-right">
                    <?php print implode(PHP_EOL, $article->getMetaDataStatusIcons($showDraftStatus, $commentEnabledGlobal, $showArchiveStatus)); ?>
                </div>
            </div>
            
        </fieldset>
    </div>
</div>


