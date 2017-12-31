<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-file-o"></span> <?php $FPCM_LANG->write('HL_CATEGORIES_MNG'); ?></h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=categories/add">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-category"><?php $FPCM_LANG->write('CATEGORIES_ADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-category">                
               <?php include __DIR__.'/categoryeditor.php'; ?>
            </div>
        </div>
    </form>
</div>             