<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-tabs-general">
    <ul>
        <li><a href="#tabs-general"><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></a></li>
    </ul>
    <div id="tabs-help-general">

        <div class="m-2">
            <?php include $theView->getIncludePath('components/progress.php'); ?>
        </div>
        
        <ul id="list"></ul>
        
        
    </div>
</div>