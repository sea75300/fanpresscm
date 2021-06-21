<div class="fpcm-ui-dataview">
    <div class="row fpcm-ui-dataview-head">
        <div class="col-1 px-0"></div>
        <div class="col-5"></div>
        <div class="col-5"><?php $theView->write('SYSTEM_OPTIONS_SYSCHECK_CURRENT'); ?></div>
        <div class="col-1 px-0"></div>
    </div>
    <?php foreach ($checkOptions as $checkOption => $checkResult) : ?>
        <div class="row fpcm-ui-dataview-row">
            <div class="col-1 fpcm-ui-center px-0 align-self-center">
                <?php if ($checkResult->isFolder()) : ?>
                    <?php print (new \fpcm\view\helper\icon('folder')); ?>
                <?php elseif ($checkResult->getHelplink()) : ?>
                    <?php $theView->shorthelpButton($checkOption)->setText('GLOBAL_INFO')->setUrl($checkResult->getHelplink())->setSize('lg'); ?>
                <?php endif; ?>
            </div>
            <div class="col-5 align-self-center">
                <spam><?php print $checkOption; ?></spam>
                <?php if ($checkResult->getActionButton() && !$checkResult->getResult()) : ?><?php print $checkResult->getActionButton(); ?><?php endif; ?>                
            </div>
            <div class="col-5 align-self-center">
                <?php print $checkResult->getCurrent(); ?>
            </div>
            <div class="col-1 fpcm-ui-center px-0 align-self-center">
                <?php $theView->boolToText(uniqid('checkres'))->setValue($checkResult->getResult())->setText($checkResult->isFolder() && $checkResult->isFolder() ? 'GLOBAL_WRITABLE' : 'GLOBAL_YES')->setSize('lg'); ?>
            </div>
        </div>    
    <?php endforeach; ?>
</div>