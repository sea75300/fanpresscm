<div class="fpcm-content-wrapper">
    
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-updater-general"><?php $theView->write('HL_PACKAGEMGR_SYSUPDATES'); ?></a></li>
        </ul>

        <div id="tabs-updater-general">
            <?php fpcm\view\helper::progressBar('fpcm-updater-progressbar'); ?>
            
            <p><span class="fa fa-arrow-circle-right fa-lg fa-fw"></span> <strong><?php $theView->write('PACKAGES_UPDATE_CURRENT_VERSION'); ?>:</strong> <?php print $theView->version; ?></p>
            <p><span class="fa fa-language fa-lg fa-fw"></span> <strong><?php $theView->write('PACKAGES_UPDATE_CURRENT_LANG'); ?>:</strong> <?php print $theView->langCode; ?></p>
            
            <div class="fpcm-updater-list"></div>
        </div>        
    </div>
    
</div>

<div id="updaterButtons" class="fpcm-ui-list-buttons">
    <div class="fpcm-ui-margin-center">
        <?php fpcm\view\helper::linkButton($theView->basePath.'system/dashboard', 'PACKAGES_BACKTODASHBOARD', '', 'fpcm-ui-pager-buttons fpcm-back-button fpcm-loader'); ?>
    </div>
</div>