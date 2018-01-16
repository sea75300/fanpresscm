<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-file-o"></span> <?php $theView->lang->write('HL_CATEGORIES_MNG'); ?></h1>
    <form method="post" action="<?php print $theView->self; ?>?module=categories/add">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-category"><?php $theView->lang->write('CATEGORIES_ADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-category">                
               <?php include __DIR__.'/categoryeditor.php'; ?>
            </div>
        </div>
    </form>
</div>             