<?php
/* @var $theView \fpcm\view\viewVars */
/* @var $userRoll \fpcm\model\users\userRoll */
?>
<div class="row border-top border-5 border-primary">
    <div class="col-12 col-md-6">
        <fieldset class="my-3">

            <div class="row">
                <?php $theView->textInput('rollname')
                    ->setValue($userRoll->getRollName())
                    ->setText('USERS_ROLLS_NAME')
                    ->setAutoFocused($userRoll->getId() && $userRoll->getId() > 3)
                    ->setReadonly($userRoll->getId() && $userRoll->getId() <= 3); ?>
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
                            ->setClass('fpcm-ui-textarea-medium fpcm-ui-full-width'); ?>                      
                </div>
            </div>
        </fieldset>
    </div>
</div>