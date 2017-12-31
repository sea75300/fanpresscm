<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-plug"></span> <?php $FPCM_LANG->write('HL_MODULES'); ?>
    </h1>
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-modules-list"><?php $FPCM_LANG->write('MODULES_LIST_HEADLINE'); ?></a></li>
            <?php if ($permissionInstall) : ?><li><a href="#tabs-modules-upload"><?php $FPCM_LANG->write('MODULES_LIST_UPLOAD'); ?></a></li><?php endif; ?>
        </ul>

        <div id="tabs-modules-list">
            <div id="modules-list-content">
                <?php include __DIR__.'/list_inner.php'; ?>
            </div>
            
            <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">                
                <div class="fpcm-ui-margin-center">
            <?php if ($moduleManagerMode) : ?>
                <?php fpcm\model\view\helper::linkButton('#', 'MODULES_LIST_RELOADPKGLIST', 'fpcm-ui-reloadpkglist', 'fpcm-ui-button-blank fpcm-reload-btn'); ?>
            <?php else : ?>
                <?php fpcm\model\view\helper::linkButton(\fpcm\classes\baseconfig::$moduleServerManualLink, 'MODULES_LIST_EXTERNALLIST', 'fpcm-ui-externalpkglist', 'fpcm-externallink-btn', '_blank'); ?>
            <?php endif; ?>
                <?php fpcm\model\view\helper::select('moduleActions', $moduleActions, '', false, true, false, 'fpcm-ui-input-select-moduleactions'); ?>
                <?php \fpcm\model\view\helper::submitButton('doAction', 'GLOBAL_OK', 'fpcm-ui-actions-modules fpcm-ok-button fpcm-loader'); ?>
                </div>
            </div>         
        </div>
        
        <?php if ($permissionInstall) : ?>
        <div id="tabs-modules-upload">
            <?php include_once dirname(__DIR__).'/filemanager/forms/phpupload.php'; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="fpcm-ui-dialog-layer fpcm-hidden" id="fpcm-dialog-modulelist-infos">  
    <table class="fpcm-ui-table">
        <tr>
            <td class="fpcm-quarter-width"><label><?php $FPCM_LANG->write('MODULES_LIST_KEY'); ?>:</label></td>
            <td colspan="3" id="fpcm-dialog-modulelist-infos-key"></td>            
        </tr>
        <tr>
            <td class="fpcm-quarter-width"><label><?php $FPCM_LANG->write('MODULES_LIST_DESCRIPTION'); ?>:</label></td>
            <td colspan="3" id="fpcm-dialog-modulelist-infos-description"></td>
        </tr>
        <tr>
            <td class="fpcm-quarter-width"><label><?php $FPCM_LANG->write('MODULES_LIST_AUTHOR'); ?>:</label></td>
            <td colspan="3" id="fpcm-dialog-modulelist-infos-author"></td>
        </tr>
        <tr>
            <td class="fpcm-quarter-width"><label><?php $FPCM_LANG->write('MODULES_LIST_LINK'); ?>:</label></td>
            <td colspan="3" id="fpcm-dialog-modulelist-infos-link"></td>
        </tr>
        <tr>
            <td class="fpcm-quarter-width"><label><?php $FPCM_LANG->write('MODULES_LIST_VERSION_LOCAL'); ?>:</label></td>
            <td id="fpcm-dialog-modulelist-infos-version" class="fpcm-quarter-width"></td>
            <td class="fpcm-quarter-width"><label><?php $FPCM_LANG->write('MODULES_LIST_VERSION_REMOTE'); ?>:</label></td>
            <td id="fpcm-dialog-modulelist-infos-versionrem" class="fpcm-quarter-width"></td>            
        </tr>
        <tr>
            <td class="fpcm-quarter-width"><label><?php $FPCM_LANG->write('MODULES_LIST_DEPENCIES'); ?>:</label></td>
            <td colspan="3" id="fpcm-dialog-modulelist-infos-dependencies"></td>
        </tr>
    </table>
</div>