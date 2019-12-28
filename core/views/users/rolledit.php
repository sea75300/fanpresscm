<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-roll"><?php $theView->write($tabsHeadline); ?></a></li>
            <?php if ($theView->permissions->system->permissions && $userRoll->getId()) : ?>
            <li><a href="#tabs-permissions"><?php $theView->write('HL_OPTIONS_PERMISSIONS'); ?></a></li>
            <?php endif; ?>
        </ul>            

        <div id="tabs-roll">
            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12">
                    <div class="row">
                        <?php $theView->textInput('rollname')
                            ->setValue($userRoll->getRollName())
                            ->setText('USERS_ROLLS_NAME')
                            ->setDisplaySizesDefault(); ?>
                    </div>
                </div>
            </div>            
        </div>

        <?php if ($theView->permissions->system->permissions && $userRoll->getId()) : ?>
        <div id="tabs-permissions">
            <?php include $theView->getIncludePath('users/permissions_editor.php'); ?>
        </div>
        <?php endif; ?>
    </div>
</div>