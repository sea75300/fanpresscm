<?php
/* @var $theView \fpcm\view\viewVars */
/* @var $userRoll \fpcm\model\users\userRoll */
?>
<div class="row">
    <div class="col-12 col-md-6">
        <fieldset class="my-3">

            <div class="row">
                <?php $theView->textInput('rollname')
                    ->setValue($userRoll->getRollName())
                    ->setText('USERS_ROLLS_NAME')
                    ->setRequired(!$userRoll->isSystemRoll())
                    ->setAutoFocused(!$userRoll->isSystemRoll())
                    ->setReadonly($userRoll->isSystemRoll()); ?>
            </div>
               
        </fieldset>
    </div>
</div>

<div class="row g-0">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('USERS_ROLLS_CODEX'); ?></legend>

            <div class="row my-3">
                <div class="col-12 col-md-6">
                        <?php $theView->textarea('rollcodex')
                            ->setValue($userRoll->getCodex(), ENT_QUOTES | ENT_COMPAT)
                            ->setClass('fpcm-ui-textarea-medium w-100'); ?>                      
                </div>
            </div>
        </fieldset>
    </div>
</div>