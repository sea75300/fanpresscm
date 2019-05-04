<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-roll"><?php $theView->write('FILE_LIST_SMILEYADD'); ?></a></li>
        </ul>            

        <div id="tabs-roll">
            <?php include $theView->getIncludePath('smileys/editor.php'); ?>
        </div>
    </div>
</div>