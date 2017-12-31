<table class="fpcm-ui-table fpcm-ui-syscheck">
    <tr>
        <th class="fpcm-ui-center"></th>
        <th></th>
        <th class="fpcm-ui-center fpcm-ui-syscheck-current"><?php $FPCM_LANG->write('SYSTEM_OPTIONS_SYSCHECK_CURRENT'); ?></th>
        <th class="fpcm-ui-center fpcm-ui-syscheck-recommend"><?php $FPCM_LANG->write('SYSTEM_OPTIONS_SYSCHECK_RECOMMEND'); ?></th>
        <th class="fpcm-ui-center"><?php $FPCM_LANG->write('SYSTEM_OPTIONS_SYSCHECK_STATUS'); ?></th>
    </tr>
<?php foreach ($checkOptions as $checkOption => $checkResult) : ?>
    <tr>
        <td class="fpcm-ui-center"><?php if (isset($checkResult['helplink'])) : ?><?php \fpcm\model\view\helper::shortHelpButton($FPCM_LANG->translate('GLOBAL_INFO'), '', $checkResult['helplink'], '_blank'); ?><?php endif; ?></td>
        <td>
            <spam><?php print $checkOption; ?></spam>
            <?php if (isset($checkResult['actionbtn']) && !$checkResult['result']) : ?>
            <?php fpcm\model\view\helper::linkButton($checkResult['actionbtn']['link'], $checkResult['actionbtn']['description']); ?>
            <?php endif; ?>
        </td>
        <td class="fpcm-ui-center fpcm-ui-syscheck-current"><?php print $checkResult['current']; ?></td>
        <td class="fpcm-ui-center fpcm-ui-syscheck-recommend"><?php print $checkResult['recommend']; ?></td>
        <td><?php \fpcm\model\view\helper::boolToText($checkResult['result'], (isset($checkResult['isFolder']) && $checkResult['isFolder'] ? 'GLOBAL_WRITABLE' : 'GLOBAL_YES')); ?></td>
    </tr>
<?php endforeach; ?>
</table>