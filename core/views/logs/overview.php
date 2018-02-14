<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general" id="fpcm-tabs-logs">
        <ul>
            <li class="fpcm-logs-reload"><a href="<?php print $reloadBaseLink; ?>0"><?php $theView->write('HL_LOGS_SESSIONS'); ?></a></li>
            <li><a href="<?php print $reloadBaseLink; ?>1"><?php $theView->write('HL_LOGS_SYSTEM'); ?></a></li>
            <li><a href="<?php print $reloadBaseLink; ?>2"><?php $theView->write('HL_LOGS_ERROR'); ?></a></li>
            <li><a href="<?php print $reloadBaseLink; ?>3"><?php $theView->write('HL_LOGS_DATABASE'); ?></a></li>
            <li><a href="<?php print $reloadBaseLink; ?>4"><?php $theView->write('HL_LOGS_PACKAGES'); ?></a></li>
            <li><a href="<?php print $reloadBaseLink; ?>5"><?php $theView->write('HL_LOGS_CRONJOBS'); ?></a></li>
            <?php foreach ($customLogs as $customLog) : ?>
            <li><a href="<?php print $reloadBaseLink.$customLog['id']; ?>"><?php $theView->write($customLog['title']); ?></a></li>
            <?php endforeach; ?>
        </ul>         
    </div>    
</div>