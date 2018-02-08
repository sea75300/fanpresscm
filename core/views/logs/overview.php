<div class="fpcm-content-wrapper">
    <form method="post" action="<?php print $theView->self; ?>?module=system/logs">
        
        <div class="fpcm-tabs-general" id="fpcm-tabs-logs">
            <ul>
                <li class="fpcm-logs-reload"><a href="<?php print $reloadBaseLink; ?>0"><?php $theView->lang->write('HL_LOGS_SESSIONS'); ?></a></li>
                <li><a href="<?php print $reloadBaseLink; ?>1"><?php $theView->lang->write('HL_LOGS_SYSTEM'); ?></a></li>
                <li><a href="<?php print $reloadBaseLink; ?>2"><?php $theView->lang->write('HL_LOGS_ERROR'); ?></a></li>
                <li><a href="<?php print $reloadBaseLink; ?>3"><?php $theView->lang->write('HL_LOGS_DATABASE'); ?></a></li>
                <li><a href="<?php print $reloadBaseLink; ?>4"><?php $theView->lang->write('HL_LOGS_PACKAGES'); ?></a></li>
                <li><a href="<?php print $reloadBaseLink; ?>5"><?php $theView->lang->write('HL_LOGS_CRONJOBS'); ?></a></li>
                <?php foreach ($customLogs as $customLog) : ?>
                <li><a href="<?php print $reloadBaseLink.$customLog['id']; ?>"><?php $theView->lang->write($customLog['title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
            <div>
                <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php $theView->button('fpcm-logs-clear_0')->setType('button')->setText('LOGS_CLEARLOG')->setClass('fpcm-logs-clear fpcm-clear-btn')->setIcon('trash'); ?>
                    </div>
                </div>              
            </div>            
        </div>    
    </form>
</div>