<div class="fpcm-content-wrapper">
    
    <form method="post" action="<?php print $item->getEditLink(); ?>">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-category"><?php $theView->lang->write('WORDBAN_EDIT'); ?></a></li>
            </ul>            
            
            <div id="tabs-category">                
                <?php include $theView->getIncludePath('wordban/editor.php'); ?>
            </div>
        </div>
    </form>
</div>             