<div class="fpcm-content-wrapper">
    
    <form method="post" action="<?php print $theView->self; ?>?module=users/add">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-user"><?php $theView->lang->write('USERS_ADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-user">                
                <?php include $theView->getIncludePath('users/usereditor.php'); ?>
            </div>
        </div>
    </form>
</div>