<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-user-plus"></span> <?php $FPCM_LANG->write('HL_OPTIONS_USERS'); ?></h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=users/add">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-user"><?php $FPCM_LANG->write('USERS_ADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-user">                
               <?php include __DIR__.'/usereditor.php' ?>                
            </div>
        </div>
    </form>
</div>