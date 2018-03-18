<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('WORDBAN_NAME'); ?>
    </div>
    <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('wbitem[searchtext]')->setValue($item->getSearchtext()); ?>
    </div>
</div>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('WORDBAN_REPLACEMENT_TEXT'); ?>
    </div>
    <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('wbitem[replacementtext]')->setValue($item->getReplacementtext()); ?>
    </div>
</div>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('GLOBAL_ACTION_PERFORM'); ?>
    </div>
    <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
        <div class="fpcm-ui-controlgroup">
            <?php $theView->checkbox('wbitem[replacetxt]')->setText('WORDBAN_REPLACETEXT')->setSelected($item->getReplaceTxt()); ?>
            <?php $theView->checkbox('wbitem[lockarticle]')->setText('WORDBAN_APPROVE_ARTICLE')->setSelected($item->getLockArticle()); ?>
            <?php $theView->checkbox('wbitem[commentapproval]')->setText('WORDBAN_APPROVA_COMMENT')->setSelected($item->getCommentApproval()); ?>
        </div>
    </div>
</div>