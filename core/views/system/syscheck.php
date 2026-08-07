<?php
/* @var $theView \fpcm\view\viewVars */
/* @var $opt \fpcm\model\system\check\option */
?>
<div class="p-2">
<?php foreach ($checkOptions as $optName => $opt) : ?>
    <div class="list-group list-group-horizontal my-1">
        <?php if ($opt->isFolder()) : ?>
        <div class="list-group-item w-auto align-content-center list-group-item-<?php print $opt->getColor(); ?>">
            <?php print (new \fpcm\view\helper\icon('folder'))->setSize('lg'); ?>
        </div>
        <?php elseif ($opt->getHelplink()) : ?>
        <?php print $theView->shorthelpButton($optName)
                ->setText('GLOBAL_INFO')
                ->setUrl($opt->getHelplink())
                ->setSize('lg')
                ->asInline('w-auto', sprintf('list-group-item-%s', $opt->getColor())); ?>
        <?php endif; ?>
        <div class="list-group-item align-content-center col flex-grow-1 list-group-item-<?php print $opt->getColor(); ?>">
            <?php print $opt->getLabel(); ?>
            <?php if ($opt->getActionButton() && !$opt->getResult()) : ?><?php print $opt->getActionButton(); ?><?php endif; ?>
        </div>
        <div class="list-group-item align-content-center col list-group-item-<?php print $opt->getColor(); ?>">
        <?php if ($opt->isFolder() && $opt->getResult()) : ?>
            <?php $theView->write('GLOBAL_WRITABLE'); ?>
        <?php elseif ($opt->isFolder() && !$opt->getResult()) : ?>
            <?php $theView->write('GLOBAL_NOT_WRITABLE'); ?>
        <?php else : ?>
            <?php print $opt->getCurrent(); ?>
        <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
</div>


