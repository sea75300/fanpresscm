<table class="fpcm-ui-table fpcm-ui-article-revisions">
    <tr>
        <th></th>
        <th><?php $theView->write('ARTICLE_LIST_TITLE'); ?></th>
        <th><?php $theView->write('EDITOR_REVISION_DATE'); ?></th>
        <th class="fpcm-th-select-row"><?php $theView->checkbox('fpcm-select-all')->setClass('fpcm-select-allrevisions'); ?></th>
    </tr>

    <?php \fpcm\view\helper::notFoundContainer($revisions, 4); ?>

    <tr class="fpcm-td-spacer"><td></td></tr>
    <?php foreach($revisions AS $revisionTime => $revisionTitle) : ?>
        <tr>
            <td class="fpcm-ui-articlelist-open"><?php \fpcm\view\helper::linkButton($article->getEditLink().'&rev='.$revisionTime, 'EDITOR_STATUS_REVISION_SHOW', '', 'fpcm-ui-button-blank fpcm-openlink-btn'); ?></td>
            <td class="fpcm-ui-ellipsis"><strong><?php print $theView->escape(strip_tags($revisionTitle)); ?></strong></td>
            <td class="fpcm-ui-revision-time"><?php $theView->dateText($revisionTime); ?></td>
            <td class="fpcm-td-select-row"><?php $theView->checkbox('revisionIds[]', 'chbx'.$revisionTime)->setValue($revisionTime)->setClass('fpcm-list-selectboxrevisions'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>