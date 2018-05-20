<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if (!count($items)) : ?>
<p class="fpcm-ui-padding-none fpcm-ui-margin-none"><?php $theView->icon('copy', 'far')->setSize('lg')->setStack('ban fpcm-ui-important-text')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
<?php else : ?>
<div class="fpcm-accordion-pkgmanager">
    <?php foreach ($items as $value) : ?>
        <?php if (!is_object($value) || !is_array($value->text)) continue; ?>
        <h2><?php print $value->time?>: <?php print $value->pkgname; ?></h2>
        <div>
            <ul>
                <?php foreach ($value->text as $line) : ?>
                <li><?php print $line; ?></li>
                <?php endforeach; ?>                
            </ul>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<div class="row fpcm-ui-font-small fpcm-ui-margin-lg-top">
    <div class="col-12 align-self-center fpcm-ui-padding-none-left">
        <?php $theView->icon('weight')->setSize('lg'); ?>
        <?php $theView->write('FILE_LIST_FILESIZE'); ?>:
        <?php $theView->escape($size); ?>
    </div>
</div>