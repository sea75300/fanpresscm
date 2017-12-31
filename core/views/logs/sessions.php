<table class="fpcm-ui-table fpcm-ui-logs fpcm-ui-logs-sessions">
    <tr>
        <th><?php $FPCM_LANG->write('LOGS_LIST_USER'); ?></th>
        <th class="fpcm-ui-center"><?php $FPCM_LANG->write('LOGS_LIST_IPADDRESS'); ?></th>
        <th class="fpcm-ui-center"><?php $FPCM_LANG->write('LOGS_LIST_LOGIN'); ?></th>
        <th class="fpcm-ui-center"><?php $FPCM_LANG->write('LOGS_LIST_LOGOUT'); ?></th>
        <th class="fpcm-ui-center"><?php $FPCM_LANG->write('GLOBAL_EXTERNAL'); ?></th>
    </tr>
    <tr class="fpcm-td-spacer"><td></td></tr>
    <?php if (!count($sessionList)) : ?>
    <tr>
        <td colspan="5"><?php $FPCM_LANG->write('GLOBAL_NOTFOUND2'); ?></td>
    </tr>                    
    <?php endif; ?>    
    <?php foreach ($sessionList as $sessionItem) : ?>
    <tr>
        <td><?php print isset($userList[$sessionItem->getUserId()]) ? $userList[$sessionItem->getUserId()]->getDisplayName() : $FPCM_LANG->translate('GLOBAL_NOTFOUND'); ?></td>
        <td class="fpcm-ui-center"><?php print $sessionItem->getIp(); ?></td>
        <td class="fpcm-ui-center"><?php \fpcm\model\view\helper::dateText($sessionItem->getLogin()); ?></td>
        <td class="fpcm-ui-center"><?php print $sessionItem->getLogout() > 0 ? \fpcm\model\view\helper::dateText($sessionItem->getLogout()) : $FPCM_LANG->translate('LOGS_LIST_TIMEOUT'); ?></td>
        <td class="fpcm-ui-center"><?php \fpcm\model\view\helper::boolToText($sessionItem->getExternal()); ?></td>
    </tr>
    <?php endforeach; ?>
</table>