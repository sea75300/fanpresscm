<?php
/* @var $theView \fpcm\view\viewVars */
/* @var $userRoll \fpcm\model\users\userRoll */
?>
<div class="row">
    <div class="col-12">
        <fieldset class="my-3">

            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <?php $theView->textInput('rollname')
                        ->setValue($userRoll->getRollName())
                        ->setText('USERS_ROLLS_NAME')
                        ->setRequired(!$userRoll->isSystemRoll())
                        ->setAutoFocused(!$userRoll->isSystemRoll())
                        ->setReadonly($userRoll->isSystemRoll()); ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <?php $theView->textarea('rollcodex')
                        ->setValue($userRoll->getCodex(), ENT_QUOTES | ENT_COMPAT)
                        ->setClass('fpcm ui-textarea-medium ui-textarea-noresize')
                        ->setText('USERS_ROLLS_CODEX'); ?>
                </div>
            </div>
    
        </fieldset>
    </div>
</div>