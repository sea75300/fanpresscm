<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-history"></span> <?php $FPCM_LANG->write('HL_CRONJOBS'); ?>
    </h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=system/crons">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-options-general"><?php $FPCM_LANG->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></a></li>
            </ul>

            <div id="tabs-options-general">

                <table class="fpcm-ui-table fpcm-ui-logs fpcm-ui-logs-cronjobs">
                    <tr>
                        <th></th>
                        <th class="fpcm-cronjob-name"><?php $FPCM_LANG->write('CRONJOB_LIST_NAME'); ?></th>
                        <th class="fpcm-ui-center"><?php $FPCM_LANG->write('CRONJOB_LIST_INTERVAL'); ?></th>
                        <th class="fpcm-ui-center"><?php $FPCM_LANG->write('CRONJOB_LIST_LASTEXEC'); ?></th>
                        <th class="fpcm-ui-center"><?php $FPCM_LANG->write('CRONJOB_LIST_NEXTEXEC'); ?></th>
                    </tr>
                    <tr class="fpcm-td-spacer"><td></td></tr>   
                    <?php foreach ($cronjobList as $cronjob) : ?>
                    <tr <?php if ($currentTime > ($cronjob->getNextExecTime() - 60)) : ?>class="fpcm-ui-important-text"<?php endif; ?>>
                        <td class="fpcm-ui-center"><?php \fpcm\model\view\helper::linkButton('#', 'CRONJOB_LIST_EXECDEMAND', $cronjob->getCronName(), 'fpcm-ui-button-blank fpcm-cronjoblist-exec'); ?></td>
                        <td class="fpcm-cronjob-name"><?php $FPCM_LANG->write('CRONJOB_'.strtoupper($cronjob->getCronName())); ?></td>
                        <td class="fpcm-cronjob-interval fpcm-ui-center"><?php fpcm\model\view\helper::select('intervals_'.$cronjob->getCronName(), $FPCM_LANG->translate('SYSTEM_OPTIONS_CRONINTERVALS'), $cronjob->getIntervalTime(), false, false, false, 'fpcm-cronjoblist-intervals'); ?></td>
                        <td class="fpcm-ui-center"><?php \fpcm\model\view\helper::dateText($cronjob->getLastExecTime()); ?></td>
                        <td class="fpcm-ui-center"><?php \fpcm\model\view\helper::dateText($cronjob->getNextExecTime()); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>

            </div>
            
        </div>
        
    </form> 
</div>