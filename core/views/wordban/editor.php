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
                            ->setWrapper(false)
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
                            ->setWrapper(false)
                            ->setText('WORDBAN_REPLACEMENT_TEXT')
                            ->setIcon('edit')
                            ->setDisplaySizesDefault(); ?>
                    </div>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-6 col-md-3 fpcm-ui-field-label-general">
                            <?php $theView->icon('cogs'); ?>
                            <?php $theView->write('GLOBAL_ACTION_PERFORM'); ?>:
                        </label>
                        <div class="col-12 col-sm-6 col-md-9 fpcm-ui-padding-none-lr">
                            <div class="fpcm-ui-controlgroup fpcm-ui-borderradius-remove-left">
                                <?php $theView->checkbox('wbitem[replacetxt]')->setText('WORDBAN_REPLACETEXT')->setSelected($item->getReplaceTxt()); ?>
                                <?php $theView->checkbox('wbitem[lockarticle]')->setText('WORDBAN_APPROVE_ARTICLE')->setSelected($item->getLockArticle()); ?>
                                <?php $theView->checkbox('wbitem[commentapproval]')->setText('WORDBAN_APPROVA_COMMENT')->setSelected($item->getCommentApproval()); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>