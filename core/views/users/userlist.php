<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general" id="fpcm-ui-tabs-users">
        <ul>
            <li data-toolbar-buttons="1"><a href="#tabs-users-active"><?php $theView->write('USERS_LIST'); ?></a></li>
            <?php if ($theView->permissions->system->rolls) : ?><li data-toolbar-buttons="2"><a href="#tabs-users-rolls"><?php $theView->write('USERS_LIST_ROLLS'); ?></a></li><?php endif; ?>
        </ul>            

        <div id="tabs-users-active">

            <div id="fpcm-dataview-userlist-spinner" class="row g-0 align-self-center fpcm-ui-inline-loader fpcm ui-background-white-50p">
                <div class="col-12 fpcm-ui-center align-self-center">
                    <?php $theView->icon('spinner fa-inverse')->setSpinner('pulse')->setStack('circle')->setSize('2x'); ?>
                </div>
            </div>              
            
            
            <div class="row">
                
                <div class="col-12 col-lg-9 px-0">
                    <div id="fpcm-dataview-userlist"></div>
                </div>

                <div class="col-12 col-lg-3 d-none d-lg-block">
                    <?php print $userArticles; ?>
                </div>
                
            </div>
            
        </div>

        <?php if ($theView->permissions->system->rolls) : ?>
        <div id="tabs-users-rolls">
            <div id="fpcm-dataview-rollslist"></div>
        </div>
        <?php endif; ?>
    </div>

    <?php include $theView->getIncludePath('users/userlist_dialogs.php'); ?>        
</div>