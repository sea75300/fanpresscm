<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-life-ring"></span> <?php $FPCM_LANG->write('HL_BACKUPS'); ?>
    </h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=system/logs">
        
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-backups-database"><?php $FPCM_LANG->write('BACKUPS_TAB_DATABASE'); ?></a></li>
            </ul>
            <div id="tabs-backups-database">
                <table class="fpcm-ui-table fpcm-ui-backups">
                    <tr>
                        <th class="fpcm-ui-editbutton-col"></th>
                        <th><?php $FPCM_LANG->write('FILE_LIST_FILENAME'); ?></th>
                        <th><?php $FPCM_LANG->write('FILE_LIST_FILESIZE'); ?></th>
                    </tr>
                    <?php fpcm\model\view\helper::notFoundContainer($folderList, 2); ?>
                    
                    <tr class="fpcm-td-spacer"><td></td></tr>
                    <?php foreach ($folderList as $value) : ?>
                    <tr>
                        <td class="fpcm-ui-editbutton-col fpcm-ui-center"><?php \fpcm\model\view\helper::linkButton(fpcm\classes\baseconfig::$rootPath.'index.php?module=system/backups&save='.str_rot13(base64_encode($value)), 'GLOBAL_DOWNLOAD', '', 'fpcm-ui-button-blank fpcm-download-btn', '_blank'); ?></td>
                        <td><?php print basename($value); ?></td>
                        <td><?php print \fpcm\classes\tools::calcSize(filesize($value)); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>    
    </form>
</div>