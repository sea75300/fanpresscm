<div class="fpcm-content-wrapper">
    
    <form method="post" action="<?php print $theView->self; ?>?module=smileys/add">
        <div class="fpcm-ui-tabs-general">
            <ul>
                <li><a href="#tabs-roll"><?php $theView->write('FILE_LIST_SMILEYADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-roll">
                <?php include $theView->getIncludePath('smileys/editor.php'); ?>
            </div>
        </div>
    </div>             
</form>