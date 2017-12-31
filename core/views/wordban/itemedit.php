<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-ban"></span> <?php $FPCM_LANG->write('HL_OPTIONS_WORDBAN'); ?></h1>
    <form method="post" action="<?php print $item->getEditLink(); ?>">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-category"><?php $FPCM_LANG->write('WORDBAN_EDIT'); ?></a></li>
            </ul>            
            
            <div id="tabs-category">                
               <?php include __DIR__.'/itemeditor.php'; ?>                
            </div>
        </div>
    </form>
</div>             