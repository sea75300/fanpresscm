<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 align-self-center m-3">
        <div class="list-group shadow">

            <div class="list-group-item py-3">
                <div class="row row-cols-2 fpcm-ui-updater-descr">
                    <div class="col flex-grow-1 align-self-center">
                        <?php $theView->icon('lightbulb')->setClass('fpcm-ui-update-icons')->setData([
                            'step' => 'maintenanceOn',
                            'func' => 'startTimer'
                        ]); ?>

                        <?php $theView->write('PACKAGEMANAGER_MAINTENANCE_EN'); ?>
                    </div>
                    <div class="col-auto fpcm ui-updater-spinner align-self-center">

                    </div>

                </div>            
            </div>

            <?php if ($checkFs) : ?>
            <div class="list-group-item py-3">
                <div class="row row-cols-2 fpcm-ui-updater-descr">
                    <div class="col flex-grow-1 align-self-center">
                        <?php $theView->icon('medkit')->setClass('fpcm-ui-update-icons')->setData([
                            'step' => 'checkFiles'
                        ]); ?>

                        <?php $theView->write('PACKAGEMANAGER_CHECKLOCAL'); ?>
                    </div>
                    <div class="col-auto fpcm ui-updater-spinner align-self-center">

                    </div>

                </div>
            </div>
            <?php endif; ?>

            <?php if ($download) : ?>
            <div class="list-group-item py-3">
                <div class="row row-cols-2 fpcm-ui-updater-descr">
                    <div class="col flex-grow-1 align-self-center">
                        <?php $theView->icon('cloud-download-alt')->setClass('fpcm-ui-update-icons')->setData([
                            'step' => 'download'
                        ]); ?>

                        <?php $theView->write('PACKAGEMANAGER_DOWNLOAD', [
                            '{{var}}' => $pkgurl,
                            '{{var2}}' => $pkgsize,
                        ]); ?>

                    </div>
                    <div class="col-auto fpcm ui-updater-spinner align-self-center">

                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($checkPkg) : ?>
            <div class="list-group-item py-3">
                <div class="row row-cols-2 fpcm-ui-updater-descr">
                    <div class="col flex-grow-1 align-self-center">
                        <?php $theView->icon('file-signature')->setClass('fpcm-ui-update-icons')->setData([
                            'step' => 'checkPkg'
                        ]); ?>

                        <?php $theView->write('PACKAGEMANAGER_CHECKPKG', [
                            '{{var}}' => $pkgname
                        ]); ?>

                    </div>
                    <div class="col-auto fpcm ui-updater-spinner align-self-center">

                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($extract) : ?>
            <div class="list-group-item py-3">
                <div class="row row-cols-2 fpcm-ui-updater-descr">
                    <div class="col flex-grow-1 align-self-center">
                        <?php $theView->icon('file-archive', 'far')->setClass('fpcm-ui-update-icons')->setData([
                            'step' => 'extract'
                        ]); ?>

                        <?php $theView->write('PACKAGEMANAGER_EXTRACT', [
                            '{{var}}' => $pkgname
                        ]); ?>
                    </div>
                    <div class="col-auto fpcm ui-updater-spinner align-self-center">

                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($updateFs) : ?>
            <div class="list-group-item py-3">
                <div class="row row-cols-2 fpcm-ui-updater-descr">
                    <div class="col flex-grow-1 align-self-center">
                        <?php $theView->icon('copy')->setClass('fpcm-ui-update-icons')->setData([
                            'step' => 'updateFs'
                        ]); ?>

                        <?php $theView->write('PACKAGEMANAGER_UPDATEFS'); ?>
                    </div>
                    <div class="col-auto fpcm ui-updater-spinner align-self-center">

                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($updateDb) : ?>
            <div class="list-group-item py-3">
                <div class="row row-cols-2 fpcm-ui-updater-descr">
                    <div class="col flex-grow-1 align-self-center">
                        <?php $theView->icon('database')->setClass('fpcm-ui-update-icons')->setData([
                            'step' => 'updateDb'
                        ]); ?>

                        <?php $theView->write('PACKAGEMANAGER_UPDATEDB'); ?>

                    </div>
                    <div class="col-auto fpcm ui-updater-spinner align-self-center">

                    </div>
                </div>
            </div>
            <?php endif; ?>


            <?php if ($updateLog) : ?>
            <div class="list-group-item py-3">
                <div class="row row-cols-2 fpcm-ui-updater-descr">
                    <div class="col flex-grow-1 align-self-center">
                        <?php $theView->icon('file-alt', 'far')->setClass('fpcm-ui-update-icons')->setData([
                            'step' => 'updateLog'
                        ]); ?>

                        <?php $theView->write('PACKAGEMANAGER_UPDATELOG'); ?>

                    </div>
                    <div class="col-auto fpcm ui-updater-spinner align-self-center">

                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($cleanup) : ?>
            <div class="list-group-item py-3">
                <div class="row row-cols-2 fpcm-ui-updater-descr">
                    <div class="col flex-grow-1 align-self-center">
                        <?php $theView->icon('eraser')->setClass('fpcm-ui-update-icons')->setData([
                            'step' => 'cleanup'
                        ]); ?>

                        <?php $theView->write('PACKAGEMANAGER_CLEANUP'); ?>
                    </div>
                    <div class="col-auto fpcm ui-updater-spinner align-self-center">

                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="list-group-item py-3">
                <div class="row row-cols-2 fpcm-ui-updater-descr">
                    <div class="col flex-grow-1 align-self-center">
                        <?php $theView->icon('bolt')->setClass('fpcm-ui-update-icons')->setData([
                            'step' => 'maintenanceOff',
                            'after' => 'stopTimer',
                        ]); ?>
                        <?php $theView->write('PACKAGEMANAGER_MAINTENANCE_DIS'); ?>
                    </div>
                    <div class="col-auto fpcm ui-updater-spinner align-self-center">

                    </div>
                </div>
            </div>

            <div class="list-group-item disabled d-none py-3" id="fpcm-ui-update-result-1">
                <div class="row fpcm-ui-updater-descr">
                    <div class="col-12 align-self-center">
                        <?php $theView->icon('check'); ?>
                        <?php $theView->write($successMsg); ?>
                    </div>
                </div>
            </div>

            <div class="list-group-item disabled d-none py-3" id="fpcm-ui-update-result-0">
                <div class="row fpcm-ui-updater-descr">
                    <div class="col-12 align-self-center">
                        <?php $theView->icon('times'); ?>
                        <?php $theView->write($errorMsg); ?>
                    </div>
                </div>
            </div>

            <div class="list-group-item py-3 list-group-item-light">
                <div class="row fpcm-ui-updater-descr">
                    <div class="col-12 align-self-center">
                        <?php $theView->icon('clock', 'far'); ?>
                        <strong><?php $theView->write('PACKAGEMANAGER_TIMER'); ?></strong>
                        <span id="fpcm-ui-update-timer"><span class="spinner-border spinner-border-sm text-secondary" role="status"></span></span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>



