<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-padding-md-tb">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
            <legend><?php $theView->write('WORDBAN_FORM'); ?></legend>

            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12">
                    <div class="row">
                        <?php $theView->textInput('wbitem[searchtext]')
                            ->setValue($item->getSearchtext())
                            ->setText('WORDBAN_NAME')
                            ->setIcon('filter')
                            ->setDisplaySizesDefault(); ?>
                    </div>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12">
                    <div class="row">
                        <?php $theView->textInput('wbitem[replacementtext]')
                            ->setValue($item->getReplacementtext())
                            ->setText('WORDBAN_REPLACEMENT_TEXT')
                            ->setIcon('edit')
                            ->setDisplaySizesDefault(); ?>
                    </div>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12 px-0">
                    <div class="row">
                        <label class="col-12 col-sm-6 col-md-3 fpcm-ui-field-label-general">
                            <?php $theView->icon('cogs'); ?>
                            <?php $theView->write('GLOBAL_ACTION_PERFORM'); ?>:
                        </label>
                        <div class="col-12 col-sm-6 col-md-9 fpcm ui-element-min-height-md fpcm-ui-input-wrapper-inner fpcm-ui-border-grey-medium fpcm-ui-border-radius-right">
                            <?php $theView->checkbox('wbitem[replacetxt]')->setText('WORDBAN_REPLACETEXT')->setSelected($item->getReplaceTxt())->setLabelClass('mr-2'); ?>
                            <?php $theView->checkbox('wbitem[lockarticle]')->setText('WORDBAN_APPROVE_ARTICLE')->setSelected($item->getLockArticle())->setLabelClass('mr-2'); ?>
                            <?php $theView->checkbox('wbitem[commentapproval]')->setText('WORDBAN_APPROVA_COMMENT')->setSelected($item->getCommentApproval()); ?>                            
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>