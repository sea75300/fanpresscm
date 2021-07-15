<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if (!count($items)) : ?>
<p class="px-2"><?php $theView->icon('copy', 'far')->setSize('lg')->setStack('ban text-danger')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></p>
<?php else : ?>
<div class="accordion" id="fpcm-pkgmgr-log-acc">
    <?php foreach ($items as $key => $value) : ?>
    <?php if (!is_object($value) || !is_array($value->text)) continue; ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="fpcm-pkgmgr-log-head<?php print $key; ?>">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-pkgmgr-log-line<?php print $key; ?>" aria-expanded="false" aria-controls="fpcm-pkgmgr-log-line<?php print $key; ?>">
                <?php print $value->time?>: <?php print $value->pkgname; ?>
            </button>
        </h2>
        <div id="fpcm-pkgmgr-log-line<?php print $key; ?>" class="accordion-collapse collapse" aria-labelledby="fpcm-pkgmgr-log-head<?php print $key; ?>" data-bs-parent="#fpcm-pkgmgr-log-acc">
            <div class="accordion-body">
            <ul>
                <?php foreach ($value->text as $line) : ?>
                <li><?php print $colorCb($line); ?></li>
                <?php endforeach; ?>                
            </ul>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<div class="row fpcm-ui-font-small">
    <div class="col-12 align-self-center p-2">
        <?php $theView->icon('weight')->setSize('lg'); ?>
        <?php $theView->write('FILE_LIST_FILESIZE'); ?>:
        <?php $theView->escape($size); ?>
    </div>
</div>