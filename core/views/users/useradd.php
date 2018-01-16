<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-user-plus"></span> <?php $theView->lang->write('HL_OPTIONS_USERS'); ?></h1>
    <form method="post" action="<?php print $theView->self; ?>?module=users/add">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-user"><?php $theView->lang->write('USERS_ADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-user">                
               <?php include __DIR__.'/usereditor.php' ?>                
            </div>
        </div>
    </form>
</div>