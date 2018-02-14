<table class="fpcm-ui-table fpcm-ui-syscheck">
    <tr>
        <th class="fpcm-ui-center"></th>
        <th></th>
        <th class="fpcm-ui-center fpcm-ui-syscheck-current"><?php $theView->write('SYSTEM_OPTIONS_SYSCHECK_CURRENT'); ?></th>
        <th class="fpcm-ui-center"><?php $theView->write('SYSTEM_OPTIONS_SYSCHECK_STATUS'); ?></th>
    </tr>
<?php foreach ($checkOptions as $checkOption => $checkResult) : ?>
    <tr>
        <td class="fpcm-ui-center"><?php if ($checkResult->getHelplink()) : ?><?php $theView->shorthelpButton($checkOption)->setText('GLOBAL_INFO')->setUrl($checkResult->getHelplink()); ?><?php endif; ?></td>
        <td>
            <spam><?php print $checkOption; ?></spam>
            <?php if ($checkResult->getActionButton() && !$checkResult->getResult()) : ?><?php print $checkResult->getActionButton(); ?><?php endif; ?>
        </td>
        <td class="fpcm-ui-center fpcm-ui-syscheck-current"><?php print $checkResult->getCurrent(); ?></td>
        <td><?php $theView->boolToText(uniqid('checkres'))->setValue($checkResult->getResult())->setText($checkResult->isFolder() && $checkResult->isFolder() ? 'GLOBAL_WRITABLE' : 'GLOBAL_YES'); ?></td>
    </tr>
<?php endforeach; ?>
</table>