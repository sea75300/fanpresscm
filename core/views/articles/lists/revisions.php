<table class="fpcm-ui-table fpcm-ui-article-revisions">
    <tr>
        <th></th>
        <th><?php $FPCM_LANG->write('ARTICLE_LIST_TITLE'); ?></th>
        <th><?php $FPCM_LANG->write('EDITOR_REVISION_DATE'); ?></th>
        <th class="fpcm-th-select-row"><?php fpcm\model\view\helper::checkbox('fpcm-select-all', '', '', '', 'fpcm-select-allrevisions', false); ?></th>
    </tr>

    <?php \fpcm\model\view\helper::notFoundContainer($revisions, 4); ?>

    <tr class="fpcm-td-spacer"><td></td></tr>
    <?php foreach($revisions AS $revisionTime => $revisionTitle) : ?>
        <tr>
            <td class="fpcm-ui-articlelist-open"><?php \fpcm\model\view\helper::linkButton($article->getEditLink().'&rev='.$revisionTime, 'EDITOR_STATUS_REVISION_SHOW', '', 'fpcm-ui-button-blank fpcm-openlink-btn'); ?></td>
            <td class="fpcm-ui-ellipsis"><strong><?php print \fpcm\model\view\helper::escapeVal(strip_tags($revisionTitle)); ?></strong></td>
            <td class="fpcm-ui-revision-time"><?php fpcm\model\view\helper::dateText($revisionTime); ?></td>
            <td class="fpcm-td-select-row"><?php fpcm\model\view\helper::checkbox('revisionIds[]', 'fpcm-list-selectboxrevisions', $revisionTime, '', 'chbx'.$revisionTime, false) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if ($revisionPermission) : ?>                
<div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
    <div class="fpcm-ui-margin-center">
        <?php fpcm\model\view\helper::submitButton('articleRevisionRestore', 'EDITOR_REVISION_RESTORE', 'fpcm-ui-revision-restore fpcm-loader'); ?>
        <?php fpcm\model\view\helper::deleteButton('revisionDelete'); ?>
    </div>
</div>
<?php endif; ?>