<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row justify-content-center">
    <div class="col-12 align-self-center m-3">
        <div class="list-group list-group-horizontal-md shadow row-cols-md-4">
            <div class="list-group-item py-3 list-group-item-light">
                <?php $theView->icon('plus-circle'); ?>
                <?php print $theView->escapeVal($pkgname); ?>
            </div>
            <div class="list-group-item py-3 list-group-item-light">
                <strong><?php $theView->write('FILE_LIST_FILESIZE'); ?></strong>
                <?php print $theView->escapeVal($pkgsize); ?>
            </div>
            <div class="list-group-item py-3 list-group-item-light">
                <?php $theView->icon('clock', 'far'); ?>
                <strong><?php $theView->write('PACKAGEMANAGER_TIMER'); ?></strong>
            </div>
            <div class="list-group-item py-3 list-group-item-light">
                <span id="fpcm-id-update-timer"><span class="spinner-border spinner-border-sm text-secondary" role="status"></span></span>
            </div>
        </div>

        <div class="my-3">
            <div id="fpcm-progress-package" class="progress fpcm ui-progressbar-lg position-relative shadow" role="progressbar" aria-label="<?php $theView->write('HL_PACKAGEMGR_SYSUPDATES'); ?>" aria-valuenow="1" aria-valuemin="1" aria-valuemax="<?php print $stepcount; ?>">
                <div class="progress-bar overflow-visible text-start"></div>
                <div class="position-absolute top-50 start-50 translate-middle <?php if (!$theView->darkMode) : ?>bg-light bg-opacity-75<?php endif; ?> p-1 rounded" id="progress-bar-label"></div>
            </div>
        </div>

        <div id="fpcm-id-package-messages"></div>
    </div>

</div>