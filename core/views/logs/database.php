<table class="fpcm-ui-table fpcm-ui-logs">
    <tr>
        <th><?php $FPCM_LANG->write('LOGS_LIST_TIME'); ?></th>
        <th><?php $FPCM_LANG->write('LOGS_LIST_TEXT'); ?></th>
    </tr>
    
    <?php \fpcm\model\view\helper::notFoundContainer($databaseLogs, 2); ?>

    <tr class="fpcm-td-spacer"><td></td></tr>  
    <?php foreach ($databaseLogs as $value) : ?>
    <?php if (!is_object($value)) continue; ?>
    <tr>
        <td><?php print $value->time; ?></td>
        <td class="fpcm-ui-monospace"><?php print nl2br(\fpcm\model\view\helper::escapeVal($value->text)); ?></td>
    </tr>
    <?php endforeach; ?>
</table>