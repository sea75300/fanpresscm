<?php
/* @var $theView \fpcm\view\viewVars */ 
/* @var $opt \fpcm\model\system\check\option */ 
?>
<div class="fpcm-ui-dataview">
    <?php foreach ($checkOptions as $optName => $opt) : ?>
        <div class="row py-3 border-bottom border-1 border-secondary fpcm ui-background-transition">
            <div class="col-auto align-self-center text-center">
                <?php if ($opt->isFolder()) : ?>
                    <span class="p-3"><?php print (new \fpcm\view\helper\icon('folder')); ?></span>
                <?php elseif ($opt->getHelplink()) : ?>
                    <?php $theView->shorthelpButton($optName)->setText('GLOBAL_INFO')->setUrl($opt->getHelplink())->setSize('lg')->setClass('btn-sm'); ?>
                <?php endif; ?>
            </div>
            <div class="col flex-grow-1 align-self-center">
                <?php print $opt->getLabel(); ?>
                <?php if ($opt->getActionButton() && !$opt->getResult()) : ?><?php print $opt->getActionButton(); ?><?php endif; ?>                
            </div>
            <div class="col flex-grow-1 align-self-center">
                <?php print $opt->getCurrent(); ?>
            </div>
            <div class="col-auto align-self-center text-center">
                <?php $theView->boolToText(uniqid('checkres'))
                        ->setValue($opt->getResult())
                        ->setText($opt->isFolder() && $opt->isFolder() ? 'GLOBAL_WRITABLE' : 'GLOBAL_YES')
                        ->setSize('2x'); ?>
            </div>
        </div>    
    <?php endforeach; ?>
</div>
