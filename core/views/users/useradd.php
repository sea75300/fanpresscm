<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-user"><?php $theView->write('USERS_ADD'); ?></a></li>
        </ul>            

        <div id="tabs-user">                
            <?php include $theView->getIncludePath('users/usereditor.php'); ?>
        </div>
    </div>
</div>