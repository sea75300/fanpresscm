<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="my-2">
    <legend class="fpcm-ui-font-small"><?php $theView->write('GLOBAL_METADATA'); ?></legend>

    <div class="row my-2 fpcm-ui-font-small">

        <div class="col align-self-center fpcm-ui-ellipsis">
            <?php $theView->icon('calendar'); ?> <?php print $createInfo; ?><br>
            <?php $theView->icon('clock', 'far'); ?> <?php print $changeInfo; ?>

        </div>

        <div class="col align-self-center text-center">
            <?php print implode(PHP_EOL, $article->getMetaDataStatusIcons($showDraftStatus, $commentEnabledGlobal, $showArchiveStatus)); ?>

        </div>

    </div>

</fieldset>