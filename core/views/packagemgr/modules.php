<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="border-top border-5 border-primary">
    <div class="row py-2 opacity-25">
        <div class="col-1 text-center">
            <?php $theView->icon('lightbulb')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                'step' => 'maintenanceOn',
                'func' => 'startTimer'
            ]); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write('PACKAGEMANAGER_MAINTENANCE_EN'); ?>
        </div>
    </div>

    <?php if ($checkFs) : ?>
    <div class="row py-2 opacity-25">
        <div class="col-1 text-center">
            <?php $theView->icon('medkit ')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                'step' => 'checkFiles'
            ]); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write('PACKAGEMANAGER_CHECKLOCAL'); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($download) : ?>
    <div class="row py-2 opacity-25">
        <div class="col-1 text-center">
            <?php $theView->icon('cloud-download-alt')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                'step' => 'download'
            ]); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write('PACKAGEMANAGER_DOWNLOAD', [
                '{{var}}' => $pkgurl,
                '{{var2}}' => $pkgsize,
            ]); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($checkPkg) : ?>
    <div class="row py-2 opacity-25">
        <div class="col-1 text-center">
            <?php $theView->icon('file-signature')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                'step' => 'checkPkg'
            ]); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write('PACKAGEMANAGER_CHECKPKG', [
                '{{var}}' => $pkgname
            ]); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($extract) : ?>
    <div class="row py-2 opacity-25">
        <div class="col-1 text-center">
            <?php $theView->icon('file-archive', 'far')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                'step' => 'extract'
            ]); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write('PACKAGEMANAGER_EXTRACT', [
                '{{var}}' => $pkgname
            ]); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($updateFs) : ?>
    <div class="row py-2 opacity-25">
        <div class="col-1 text-center">
            <?php $theView->icon('copy')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                'step' => 'updateFs'
            ]); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write('PACKAGEMANAGER_UPDATEFS'); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($updateDb) : ?>
    <div class="row py-2 opacity-25">
        <div class="col-1 text-center">
            <?php $theView->icon('database')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                'step' => 'updateDb'
            ]); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write('PACKAGEMANAGER_UPDATEDB'); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($updateLog) : ?>
    <div class="row py-2 opacity-25">
        <div class="col-1 text-center">
            <?php $theView->icon('file-alt', 'far')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                'step' => 'updateLog'
            ]); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write('PACKAGEMANAGER_UPDATELOG'); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($cleanup) : ?>
    <div class="row py-2 opacity-25">
        <div class="col-1 text-center">
            <?php $theView->icon('eraser')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                'step' => 'cleanup'
            ]); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write('PACKAGEMANAGER_CLEANUP'); ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="row py-2 opacity-25">
        <div class="col-1 text-center">
            <?php $theView->icon('lightbulb')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                'step' => 'maintenanceOff',
                'after' => 'stopTimer',
            ]); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write('PACKAGEMANAGER_MAINTENANCE_DIS'); ?>
        </div>
    </div>

    <div class="row py-2 fpcm-ui-hidden">
        <div class="col-1 text-center">
            <?php $theView->icon('clock', 'far')->setSize('2x'); ?>
        </div>
        <div class="col-auto align-self-center" id="fpcm-ui-update-timer">
            <strong><?php $theView->write('PACKAGEMANAGER_TIMER'); ?></strong>
        </div>
    </div>

    <div class="row py-2 fpcm-ui-hidden" id="fpcm-ui-update-result-1">
        <div class="col-1 text-center">
            <?php $theView->icon('check')->setSize('3x')->setClass('fpcm-ui-editor-metainfo'); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write($successMsg); ?>
        </div>
    </div>

    <div class="row py-2 fpcm-ui-hidden" id="fpcm-ui-update-result-0">
        <div class="col-1 text-center">
            <?php $theView->icon('times')->setSize('3x')->setClass('text-danger'); ?>
        </div>
        <div class="col-auto align-self-center fpcm-ui-updater-descr">
            <?php $theView->write($errorMsg); ?>
        </div>
    </div>
</div>