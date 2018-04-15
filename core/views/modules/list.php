<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-modules-list"><?php $theView->write('MODULES_LIST_HEADLINE'); ?></a></li>
            <?php if ($permissionInstall) : ?><li><a href="#tabs-modules-upload"><?php $theView->write('MODULES_LIST_UPLOAD'); ?></a></li><?php endif; ?>
        </ul>

        <div id="tabs-modules-list">
            <div id="fpcm-dataview-modulelist"></div>       
        </div>
        
        <?php if ($permissionInstall) : ?>
        <div id="tabs-modules-upload">
            <?php include $theView->getIncludePath('filemanager/forms/phpupload.php'); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden" id="fpcm-dialog-modulelist-infos">  
    <table class="fpcm-ui-table">
        <tr>
            <td class="fpcm-quarter-width"><label><?php $theView->write('MODULES_LIST_KEY'); ?>:</label></td>
            <td colspan="3" id="fpcm-dialog-modulelist-infos-key"></td>            
        </tr>
        <tr>
            <td class="fpcm-quarter-width"><label><?php $theView->write('MODULES_LIST_DESCRIPTION'); ?>:</label></td>
            <td colspan="3" id="fpcm-dialog-modulelist-infos-description"></td>
        </tr>
        <tr>
            <td class="fpcm-quarter-width"><label><?php $theView->write('MODULES_LIST_AUTHOR'); ?>:</label></td>
            <td colspan="3" id="fpcm-dialog-modulelist-infos-author"></td>
        </tr>
        <tr>
            <td class="fpcm-quarter-width"><label><?php $theView->write('MODULES_LIST_LINK'); ?>:</label></td>
            <td colspan="3" id="fpcm-dialog-modulelist-infos-link"></td>
        </tr>
        <tr>
            <td class="fpcm-quarter-width"><label><?php $theView->write('MODULES_LIST_VERSION_LOCAL'); ?>:</label></td>
            <td id="fpcm-dialog-modulelist-infos-version" class="fpcm-quarter-width"></td>
            <td class="fpcm-quarter-width"><label><?php $theView->write('MODULES_LIST_VERSION_REMOTE'); ?>:</label></td>
            <td id="fpcm-dialog-modulelist-infos-versionrem" class="fpcm-quarter-width"></td>            
        </tr>
        <tr>
            <td class="fpcm-quarter-width"><label><?php $theView->write('MODULES_LIST_DEPENCIES'); ?>:</label></td>
            <td colspan="3" id="fpcm-dialog-modulelist-infos-dependencies"></td>
        </tr>
    </table>
</div>