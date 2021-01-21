<?php
/* @var $theView \fpcm\view\viewVars */
/* @var $userRoll \fpcm\model\users\userRoll */
?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-roll"><?php $theView->write($tabsHeadline); ?></a></li>
            <?php if ($theView->permissions->system->permissions && $userRoll->getId()) : ?>
            <li><a href="#tabs-permissions"><?php $theView->write('HL_OPTIONS_PERMISSIONS'); ?></a></li>
            <?php endif; ?>
        </ul>            

        <div id="tabs-roll">
            <div class="row mb-2">
                <?php $theView->textInput('rollname')
                    ->setValue($userRoll->getRollName())
                    ->setText('USERS_ROLLS_NAME')
                    ->setAutoFocused($userRoll->getId() && $userRoll->getId() > 3)
                    ->setReadonly($userRoll->getId() && $userRoll->getId() <= 3)
                    ->setDisplaySizesDefault(); ?>
            </div>
            <div class="row mb-2">
                <div class="col-12 px-0">
                    <fieldset>
                        <legend><?php $theView->write('USERS_ROLLS_CODEX'); ?></legend>
                        <?php $theView->textarea('rollcodex')
                            ->setValue($userRoll->getCodex(), ENT_QUOTES | ENT_COMPAT)
                            ->setClass('fpcm-ui-textarea-medium fpcm-ui-full-width'); ?>
                    </fieldset>
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