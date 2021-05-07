<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row g-0 fpcm-ui-padding-md-tb">
    <div class="col-12">
        <fieldset class="py-2">
            <legend><?php $theView->write('WORDBAN_FORM'); ?></legend>

            <div class="row py-2">
                <?php $theView->textInput('wbitem[searchtext]')
                    ->setValue($item->getSearchtext())
                    ->setText('WORDBAN_NAME')
                    ->setIcon('filter'); ?>
            </div>

            <div class="row py-2">
                <?php $theView->textInput('wbitem[replacementtext]')
                    ->setValue($item->getReplacementtext())
                    ->setText('WORDBAN_REPLACEMENT_TEXT')
                    ->setIcon('edit'); ?>
            </div>

            <div class="row py-2">
                <label class="col-12 col-sm-6 col-md-3 fpcm-ui-field-label-general">
                    <?php $theView->icon('cogs'); ?>
                    <?php $theView->write('GLOBAL_ACTION_PERFORM'); ?>:
                </label>
                <div class="col-12 col-sm-6 col-md-9 fpcm ui-element-min-height-md fpcm-ui-input-wrapper-inner fpcm-ui-border-grey-medium fpcm-ui-border-radius-all">
                    <?php $theView->checkbox('wbitem[replacetxt]')->setText('WORDBAN_REPLACETEXT')->setSelected($item->getReplaceTxt())->setLabelClass('me-2'); ?>
                    <?php $theView->checkbox('wbitem[lockarticle]')->setText('WORDBAN_APPROVE_ARTICLE')->setSelected($item->getLockArticle())->setLabelClass('me-2'); ?>
                    <?php $theView->checkbox('wbitem[commentapproval]')->setText('WORDBAN_APPROVA_COMMENT')->setSelected($item->getCommentApproval()); ?>                            
                </div>
            </div>
        </fieldset>
    </div>
</div>