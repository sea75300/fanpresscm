<div class="fpcm-content-wrapper">
    <form method="post" action="<?php print $category->getEditLink(); ?>">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-category"><?php $theView->lang->write('CATEGORIES_EDIT'); ?></a></li>
            </ul>            
            
            <div id="tabs-category">                
               <?php include $theView->getIncludePath('categories/editor.php'); ?>
            </div>
        </div>
    </form>
</div>