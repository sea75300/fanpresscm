<table class="fpcm-ui-table">
    <tr>
        <td><?php $FPCM_LANG->write('WORDBAN_NAME'); ?>:</td>
        <td><?php \fpcm\model\view\helper::textInput('wbitem[searchtext]','',$item->getSearchtext()); ?></td>
    </tr>
    <tr>
        <td><?php $FPCM_LANG->write('WORDBAN_ICON_PATH'); ?>:</td>
        <td><?php \fpcm\model\view\helper::textInput('wbitem[replacementtext]','',$item->getReplacementtext()); ?></td>
    </tr>
    <tr>
        <td><?php $FPCM_LANG->write('GLOBAL_ACTION_PERFORM'); ?>:</td>
        <td class="fpcm-ui-buttonset">
            <?php fpcm\model\view\helper::checkbox('wbitem[replacetxt]', '', '1', 'WORDBAN_REPLACETEXT', 'replacetxt', $item->getReplaceTxt()); ?> 
            <?php fpcm\model\view\helper::checkbox('wbitem[lockarticle]', '', '1', 'WORDBAN_APPROVE_ARTICLE', 'lockarticle', $item->getLockArticle()); ?> 
            <?php fpcm\model\view\helper::checkbox('wbitem[commentapproval]', '', '1', 'WORDBAN_APPROVA_COMMENT', 'commentapproval', $item->getCommentApproval()); ?> 
        </td>
    </tr> 
</table> 

<div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
    <div class="fpcm-ui-margin-center">
        <?php \fpcm\model\view\helper::saveButton('wbitemSave'); ?>
    </div>
</div>

<?php \fpcm\model\view\helper::pageTokenField(); ?>