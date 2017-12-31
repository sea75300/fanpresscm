<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-smile-o"></span> <?php $FPCM_LANG->write('HL_OPTIONS_SMILEYS'); ?></h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=smileys/add">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-roll"><?php $FPCM_LANG->write('FILE_LIST_SMILEYADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-roll">
                <?php include __DIR__.'/editor.php';?>
            </div>
        </div>
    </div>             
</form>