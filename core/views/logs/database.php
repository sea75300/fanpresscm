<table class="fpcm-ui-table fpcm-ui-logs">
    <tr>
        <th><?php $theView->lang->write('LOGS_LIST_TIME'); ?></th>
        <th><?php $theView->lang->write('LOGS_LIST_TEXT'); ?></th>
    </tr>
    
    <?php \fpcm\view\helper::notFoundContainer($databaseLogs, 2); ?>

    <tr class="fpcm-td-spacer"><td></td></tr>  
    <?php foreach ($databaseLogs as $value) : ?>
    <?php if (!is_object($value)) continue; ?>
    <tr>
        <td><?php print $value->time; ?></td>
        <td class="fpcm-ui-monospace"><?php print nl2br($theView->escape($value->text)); ?></td>
    </tr>
    <?php endforeach; ?>
</table>