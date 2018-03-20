<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-updater-general"><?php $theView->write('HL_PACKAGEMGR_SYSUPDATES'); ?></a></li>
        </ul>

        <div id="tabs-updater-general">

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('code-fork')->setSize('2x'); ?>
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
                    <?php $theView->icon('lightbulb-o')->setSize('2x')->setData([
                        'step' => 'maintenance_on',
                        'next' => 'checkfiles',
                        'func' => 'startTimer'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_MAINTENANCE_EN'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('files-o')->setSize('2x')->setData([
                        'step' => 'checkfiles',
                        'next' => 'download'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_CHECKLOCAL'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('cloud-download')->setSize('2x')->setData([
                        'step' => 'download',
                        'next' => 'checkpkg'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_DOWNLOAD'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('info')->setSize('2x')->setData([
                        'step' => 'checkpkg',
                        'next' => 'extract'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_CHECKPKG'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('file-archive-o')->setSize('2x')->setData([
                        'step' => 'extract',
                        'next' => 'updatefs'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_EXTRACT'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('random')->setSize('2x')->setData([
                        'step' => 'updatefs',
                        'next' => 'updatedb'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_UPDATEFS'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('refresh')->setSize('2x')->setData([
                        'step' => 'updatedb',
                        'next' => 'updatelog'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_UPDATEDB'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('file-text')->setSize('2x')->setData([
                        'step' => 'updatelog',
                        'next' => 'cleanup'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_UPDATELOG'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('eraser')->setSize('2x')->setData([
                        'step' => 'cleanup',
                        'next' => 'maintenance_on'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_CLEANUP'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb fpcm-ui-status-0">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('lightbulb-o')->setSize('2x')->setData([
                        'step' => 'maintenance_on',
                        'next' => 'getversion'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_MAINTENANCE_DIS'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('code-fork')->setSize('2x')->setData([
                        'step' => 'getversion',
                        'next' => false,
                        'func' => 'stopTimer'
                    ]); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_NEWVERSION'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('clock-o')->setSize('2x'); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_TIMER'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('check')->setSize('2x'); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_SUCCESS'); ?>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb ">
                <div class="col-1 fpcm-ui-padding-none-lr fpcm-ui-center">
                    <?php $theView->icon('times')->setSize('2x'); ?>
                </div>
                <div class="col-11 align-self-center">
                    <?php $theView->write('PACKAGEMANAGER_FAILED'); ?>
                </div>
            </div>

        </div>        
    </div>
    
</div>