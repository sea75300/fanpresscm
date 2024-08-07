<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-dataview">
    <?php foreach ($checkOptions as $checkOption => $checkResult) : ?>
        <div class="row py-3 border-bottom border-1 border-secondary fpcm ui-background-transition">
            <div class="col-auto flex-grow-1 align-self-center text-center">
                <?php if ($checkResult->isFolder()) : ?>
                    <span class="p-3"><?php print (new \fpcm\view\helper\icon('folder')); ?></span>
                <?php elseif ($checkResult->getHelplink()) : ?>
                    <?php $theView->shorthelpButton($checkOption)->setText('GLOBAL_INFO')->setUrl($checkResult->getHelplink())->setSize('lg')->setClass('btn-sm'); ?>
                <?php endif; ?>
            </div>
            <div class="col-6 align-self-center">
                <?php print $checkOption; ?>
                <?php if ($checkResult->getActionButton() && !$checkResult->getResult()) : ?><?php print $checkResult->getActionButton(); ?><?php endif; ?>                
            </div>
            <div class="col-5 align-self-center">
                <?php print $checkResult->getCurrent(); ?>
            </div>
            <div class="col-auto flex-grow-1 align-self-center text-center">
                <?php $theView->boolToText(uniqid('checkres'))->setValue($checkResult->getResult())->setText($checkResult->isFolder() && $checkResult->isFolder() ? 'GLOBAL_WRITABLE' : 'GLOBAL_YES')->setSize('lg'); ?>
            </div>
        </div>    
    <?php endforeach; ?>
</div>
