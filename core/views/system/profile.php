<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-wrench"></span> <?php $theView->lang->write('HL_PROFILE'); ?>
    </h1>
    <form method="post" action="<?php print $theView->self; ?>?module=system/profile" enctype="multipart/form-data">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-user"><?php $theView->lang->write('HL_PROFILE'); ?></a></li>
                <li><a href="#tabs-user-meta"><?php $theView->lang->write('USERS_META_OPTIONS'); ?></a></li>
            </ul>            
            
            <div id="tabs-user">
                <?php include $theView->getIncludePath('users/usereditor.php'); ?>
            </div>
            
            <div id="tabs-user-meta">
                <?php include $theView->getIncludePath('/users/editormeta.php'); ?>
            </div>            
        </div>
        
        <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
            <div class="fpcm-ui-margin-center">
                <?php \fpcm\view\helper::saveButton('profileSave'); ?>
                <?php \fpcm\view\helper::submitButton('resetProfileSettings', 'GLOBAL_RESET', 'fpcm-profilereset-btn'); ?>
            </div>
        </div>
    </form>
</div>