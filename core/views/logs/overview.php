<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-exclamation-triangle"></span> <?php $FPCM_LANG->write('HL_LOGS'); ?>
    </h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=system/logs">
        
        <div class="fpcm-tabs-general" id="fpcm-tabs-logs">
            <ul>
                <li class="fpcm-logs-reload"><a href="<?php print $reloadBaseLink; ?>0"><?php $FPCM_LANG->write('HL_LOGS_SESSIONS'); ?></a></li>
                <li><a href="<?php print $reloadBaseLink; ?>1"><?php $FPCM_LANG->write('HL_LOGS_SYSTEM'); ?></a></li>
                <li><a href="<?php print $reloadBaseLink; ?>2"><?php $FPCM_LANG->write('HL_LOGS_ERROR'); ?></a></li>
                <li><a href="<?php print $reloadBaseLink; ?>3"><?php $FPCM_LANG->write('HL_LOGS_DATABASE'); ?></a></li>
                <li><a href="<?php print $reloadBaseLink; ?>4"><?php $FPCM_LANG->write('HL_LOGS_PACKAGES'); ?></a></li>
                <li><a href="<?php print $reloadBaseLink; ?>5"><?php $FPCM_LANG->write('HL_LOGS_CRONJOBS'); ?></a></li>
                <?php foreach ($customLogs as $customLog) : ?>
                <li><a href="<?php print $reloadBaseLink.$customLog['id']; ?>"><?php $FPCM_LANG->write($customLog['title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
            <div>
                <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php fpcm\model\view\helper::submitButton('fpcm-logs-clear_0', 'LOGS_CLEARLOG', 'fpcm-logs-clear fpcm-clear-btn'); ?>
                    </div>
                </div>              
            </div>            
        </div>    
    </form>
</div>