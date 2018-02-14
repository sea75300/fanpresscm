<table class="fpcm-ui-table">
    <tr>
        <td><?php $theView->write('WORDBAN_NAME'); ?>:</td>
        <td><?php \fpcm\view\helper::textInput('wbitem[searchtext]','',$item->getSearchtext()); ?></td>
    </tr>
    <tr>
        <td><?php $theView->write('WORDBAN_ICON_PATH'); ?>:</td>
        <td><?php \fpcm\view\helper::textInput('wbitem[replacementtext]','',$item->getReplacementtext()); ?></td>
    </tr>
    <tr>
        <td><?php $theView->write('GLOBAL_ACTION_PERFORM'); ?>:</td>
        <td class="fpcm-ui-toolbar">
            <?php $theView->checkbox('wbitem[replacetxt]')->setText('WORDBAN_REPLACETEXT')->setSelected($item->getReplaceTxt()); ?>
            <?php $theView->checkbox('wbitem[lockarticle]')->setText('WORDBAN_APPROVE_ARTICLE')->setSelected($item->getLockArticle()); ?>
            <?php $theView->checkbox('wbitem[commentapproval]')->setText('WORDBAN_APPROVA_COMMENT')->setSelected($item->getCommentApproval()); ?>
        </td>
    </tr> 
</table> 

<?php $theView->pageTokenField('pgtkn'); ?>