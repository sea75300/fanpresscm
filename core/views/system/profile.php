<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-full-height">
    <div class="col-12">
        <div class="fpcm-content-wrapper fpcm-ui-full-height">
            <div class="fpcm-ui-tabs-general">
                <ul>
                    <li><a href="#tabs-user"><?php $theView->write('HL_PROFILE'); ?></a></li>
                    <li><a href="#tabs-user-meta"><?php $theView->write('USERS_META_OPTIONS'); ?></a></li>
                </ul>            

                <div id="tabs-user">
                    <?php include $theView->getIncludePath('users/usereditor.php'); ?>
                </div>

                <div id="tabs-user-meta">
                    <?php include $theView->getIncludePath('/users/editormeta.php'); ?>
                </div>            
            </div>
        </div>
    </div>
</div>