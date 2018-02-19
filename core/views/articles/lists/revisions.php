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
            <td class="fpcm-ui-articlelist-open"><?php $theView->linkButton('rev'.$revisionTime)->setUrl($article->getEditLink().'&rev='.$revisionTime)->setText('EDITOR_STATUS_REVISION_SHOW')->setIcon('play')->setIconOnly(true); ?></td>
            <td class="fpcm-ui-ellipsis"><strong><?php print $theView->escape(strip_tags($revisionTitle)); ?></strong></td>
            <td class="fpcm-ui-revision-time"><?php $theView->dateText($revisionTime); ?></td>
            <td class="fpcm-td-select-row"><?php $theView->checkbox('revisionIds[]', 'chbx'.$revisionTime)->setValue($revisionTime)->setClass('fpcm-list-selectboxrevisions'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>