<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-wrench"></span> <?php $FPCM_LANG->write('HL_PROFILE'); ?>
    </h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=system/profile" enctype="multipart/form-data">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-user"><?php $FPCM_LANG->write('HL_PROFILE'); ?></a></li>
                <li><a href="#tabs-user-meta"><?php $FPCM_LANG->write('USERS_META_OPTIONS'); ?></a></li>
            </ul>            
            
            <div id="tabs-user">                
               <?php include dirname(__DIR__).'/users/usereditor.php' ?>                
            </div>
            
            <div id="tabs-user-meta">                
               <?php include dirname(__DIR__).'/users/editormeta.php' ?>                
            </div>            
        </div>
        
        <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
            <div class="fpcm-ui-margin-center">
                <?php \fpcm\model\view\helper::saveButton('profileSave'); ?>
                <?php \fpcm\model\view\helper::submitButton('resetProfileSettings', 'GLOBAL_RESET', 'fpcm-profilereset-btn'); ?>
            </div>
        </div>
    </form>
</div>