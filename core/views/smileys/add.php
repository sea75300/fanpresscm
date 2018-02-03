<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-smile-o"></span> <?php $theView->lang->write('HL_OPTIONS_SMILEYS'); ?></h1>
    <form method="post" action="<?php print $theView->self; ?>?module=smileys/add">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-roll"><?php $theView->lang->write('FILE_LIST_SMILEYADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-roll">
                <?php include $theView->getIncludePath('smileys/editor.php'); ?>
            </div>
        </div>
    </div>             
</form>