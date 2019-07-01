<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row fpcm-ui-padding-md-tb no-gutters">
    <div class="col-12">
        <div class="row">
            <?php $theView->textInput('wbitem[searchtext]')
                ->setValue($item->getSearchtext())
                ->setWrapper(false)
                ->setText('WORDBAN_NAME')
                ->setIcon('filter')
                ->setClass('col-12 col-md-10 fpcm-ui-field-input-nowrapper-general')
                ->setLabelClass('col-12 col-md-2 fpcm-ui-field-label-general'); ?>
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
                ->setClass('col-12 col-md-10 fpcm-ui-field-input-nowrapper-general')
                ->setLabelClass('col-12 col-md-2 fpcm-ui-field-label-general'); ?>
        </div>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-12 fpcm-ui-padding-none-lr">
        <div class="row">
            <label class="col-12 col-md-2 fpcm-ui-field-label-general">
                <?php $theView->icon('cogs'); ?>
                <?php $theView->write('GLOBAL_ACTION_PERFORM'); ?>:
            </label>
            <div class="col-12 col-md-10 fpcm-ui-padding-none-lr">
                <div class="fpcm-ui-controlgroup">
                    <?php $theView->checkbox('wbitem[replacetxt]')->setText('WORDBAN_REPLACETEXT')->setSelected($item->getReplaceTxt()); ?>
                    <?php $theView->checkbox('wbitem[lockarticle]')->setText('WORDBAN_APPROVE_ARTICLE')->setSelected($item->getLockArticle()); ?>
                    <?php $theView->checkbox('wbitem[commentapproval]')->setText('WORDBAN_APPROVA_COMMENT')->setSelected($item->getCommentApproval()); ?>
                </div>
            </div>
        </div>
    </div>
</div>