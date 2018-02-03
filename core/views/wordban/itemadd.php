<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-ban"></span> <?php $theView->lang->write('HL_OPTIONS_WORDBAN'); ?></h1>
    <form method="post" action="<?php print $theView->self; ?>?module=wordban/add">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-category"><?php $theView->lang->write('WORDBAN_ADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-category">                
                <?php include $theView->getIncludePath('wordban/editor.php'); ?>
            </div>
        </div>
    </form>
</div>