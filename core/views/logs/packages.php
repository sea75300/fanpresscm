<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row bg-primary-subtle py-1 fpcm ui-dataview-head">
    <div class="text-center text-md-start align-self-center py-0 py-md-1 col">
        <?php $theView->write('LOGS_LIST_TEXT'); ?>
    </div>
</div>

<?php if (!count($items)) : ?>
<p class="p-3 border-1 border-bottom border-secondary"><?php $theView->icon('copy', 'far')->setSize('lg')->setStack('ban text-danger')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
<?php else : ?>
<?php

    $items = array_filter($items, function ($value) {
        return is_object($value) && is_array($value->text);
    });

    array_walk($items, function(&$item, $key) use ($colorCb) {
        $lines = array_map(function ($line) use ($colorCb) {

            return '<li>' . $colorCb($line) . '</li>';

        }, $item->text);
        $item = (new fpcm\view\helper\accordionItem('pkgmgr-log-' . $key))->setText("{$item->time}: {$item->pkgname}")->setValue('<ul>' . implode('', $lines) . '</ul>')->setParent('pkgmgr-log');
    });

    $theView->accordion('pkgmgr-log')->setItems($items);

?>

<?php endif; ?>
<div class="row fpcm-ui-font-small text-body-tertiary">
    <div class="col-12 align-self-center p-2">
        <?php $theView->icon('weight')->setSize('lg'); ?>
        <?php $theView->write('FILE_LIST_FILESIZE'); ?>:
        <?php $theView->escape($size); ?>
    </div>
</div>