<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-users"></span> <?php $theView->lang->write('HL_OPTIONS_USERS'); ?></h1>
    <form method="post" action="<?php print $author->getEditLink(); ?>" enctype="multipart/form-data">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-user"><?php $theView->lang->write('USERS_EDIT'); ?></a></li>
                <li><a href="#tabs-user-meta"><?php $theView->lang->write('USERS_META_OPTIONS'); ?></a></li>
            </ul>            
            
            <div id="tabs-user">                
               <?php include __DIR__.'/usereditor.php' ?>                
            </div>
            
            <div id="tabs-user-meta">                
               <?php include __DIR__.'/editormeta.php' ?>                
            </div>            
        </div>
        
        <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
            <div class="fpcm-ui-margin-center">
                <?php \fpcm\view\helper::saveButton('userSave'); ?>
                <?php \fpcm\view\helper::submitButton('resetProfileSettings', 'GLOBAL_RESET', 'fpcm-profilereset-btn'); ?>
            </div>
        </div>
    </form>
</div>