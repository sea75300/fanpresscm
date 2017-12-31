<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-refresh" id="fpcm-ui-headspinner"></span> <?php $FPCM_LANG->write('HL_PACKAGEMGR_SYSUPDATES'); ?></h1>
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-updater-general"><?php $FPCM_LANG->write('HL_PACKAGEMGR_SYSUPDATES'); ?></a></li>
        </ul>

        <div id="tabs-updater-general">
            <?php fpcm\model\view\helper::progressBar('fpcm-updater-progressbar'); ?>
            
            <p><span class="fa fa-arrow-circle-right fa-lg fa-fw"></span> <strong><?php $FPCM_LANG->write('PACKAGES_UPDATE_CURRENT_VERSION'); ?>:</strong> <?php print $FPCM_VERSION; ?></p>
            <p><span class="fa fa-language fa-lg fa-fw"></span> <strong><?php $FPCM_LANG->write('PACKAGES_UPDATE_CURRENT_LANG'); ?>:</strong> <?php print $FPCM_LANG->getLangCode(); ?></p>
            
            <div class="fpcm-updater-list"></div>
        </div>        
    </div>
    
</div>

<div id="updaterButtons" class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
    <div class="fpcm-ui-margin-center">
        <?php fpcm\model\view\helper::linkButton($FPCM_BASEMODULELINK.'system/dashboard', 'PACKAGES_BACKTODASHBOARD', '', 'fpcm-ui-pager-buttons fpcm-back-button fpcm-loader'); ?>
    </div>
</div>