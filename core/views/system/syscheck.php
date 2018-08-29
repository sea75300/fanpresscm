<div class="fpcm-ui-dataview">
    <div class="row fpcm-ui-dataview-head fpcm-ui-dataview-rowcolpadding ui-widget-header ui-corner-all ui-helper-reset">
        <div class="col-1 fpcm-ui-padding-none-lr"></div>
        <div class="col-5"></div>
        <div class="col-5"><?php $theView->write('SYSTEM_OPTIONS_SYSCHECK_CURRENT'); ?></div>
        <div class="col-1 fpcm-ui-padding-none-lr"></div>
    </div>
    <?php foreach ($checkOptions as $checkOption => $checkResult) : ?>
        <div class="row fpcm-ui-dataview-row">
            <div class="col-1 fpcm-ui-center fpcm-ui-padding-none-lr align-self-center">
                <?php if ($checkResult->getHelplink()) : ?>
                    <?php $theView->shorthelpButton($checkOption)->setText('GLOBAL_INFO')->setUrl($checkResult->getHelplink()); ?>
                <?php endif; ?>
            </div>
            <div class="col-5 align-self-center">
                <spam><?php print $checkOption; ?></spam>
                <?php if ($checkResult->getActionButton() && !$checkResult->getResult()) : ?><?php print $checkResult->getActionButton(); ?><?php endif; ?>                
            </div>
            <div class="col-5 align-self-center">
                <?php print $checkResult->getCurrent(); ?>
            </div>
            <div class="col-1 fpcm-ui-center fpcm-ui-padding-none-lr align-self-center">
                <?php $theView->boolToText(uniqid('checkres'))->setValue($checkResult->getResult())->setText($checkResult->isFolder() && $checkResult->isFolder() ? 'GLOBAL_WRITABLE' : 'GLOBAL_YES'); ?>
            </div>
        </div>    
    <?php endforeach; ?>
</div>