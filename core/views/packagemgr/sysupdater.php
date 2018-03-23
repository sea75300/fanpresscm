<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-updater-general"><?php $theView->write('HL_PACKAGEMGR_SYSUPDATES'); ?></a></li>
        </ul>

        <div id="tabs-updater-general">

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('code-fork fa-flip-vertical')->setSize('2x'); ?>
                </div>
                <div class="col-11 align-self-center">
                    <strong><?php $theView->write('PACKAGEMANAGER_CURRENTVERSION'); ?>:</strong>
                    <?php print $theView->escapeVal($theView->version); ?>
                </div>
            </div>
            
            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('language')->setSize('2x'); ?>
                </div>
                <div class="col-11 align-self-center">
                    <strong><?php $theView->write('PACKAGEMANAGER_CURRENTLANGUAGE'); ?>:</strong>
                    <?php print $theView->escapeVal($theView->langCode); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('lightbulb-o')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                        'step' => 'maintenance_on',
                        'func' => 'startTimer'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_MAINTENANCE_EN'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('files-o')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                        'step' => 'checkfiles'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_CHECKLOCAL'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('cloud-download')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                        'step' => 'download'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_DOWNLOAD'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('info')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                        'step' => 'checkpkg'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_CHECKPKG'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('file-archive-o')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                        'step' => 'extract'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_EXTRACT'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('random')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                        'step' => 'updatefs'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_UPDATEFS'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('database')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                        'step' => 'updatedb'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_UPDATEDB'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('file-text')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                        'step' => 'updatelog'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_UPDATELOG'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('eraser')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                        'step' => 'cleanup'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_CLEANUP'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('lightbulb-o')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setStackTop(true)->setData([
                        'step' => 'maintenance_on'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_MAINTENANCE_DIS'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('code-fork')->setSize('2x')->setClass('fpcm-ui-update-icons')->setStack('square-o fpcm-ui-update-iconstatus fpcm-ui-update-iconstatus-0')->setData([
                        'step' => 'getversion',
                        'after' => 'stopTimer'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_NEWVERSION'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-hidden">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('clock-o')->setSize('2x'); ?>
                </div>
                <div class="col-11 align-self-center" id="fpcm-ui-update-timer">
                    <?php $theView->write('PACKAGEMANAGER_TIMER'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-hidden" id="fpcm-ui-update-result-1">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('check')->setSize('3x')->setClass('fpcm-ui-editor-metainfo'); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_SUCCESS'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-hidden" id="fpcm-ui-update-result-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('times')->setSize('3x')->setClass('fpcm-ui-important-text'); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_FAILED'); ?>
                </div>
            </div>

        </div>        
    </div>
    
</div>