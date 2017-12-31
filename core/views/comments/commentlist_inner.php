<table class="fpcm-ui-table fpcm-ui-comments">
    <tr>
        <th <?php if ($commentsMode == 1) : ?>class="fpcm-ui-articlelist-open"<?php endif; ?>></th>
        <th><?php $FPCM_LANG->write('COMMMENT_AUTHOR'); ?></th>
        <th><?php $FPCM_LANG->write('GLOBAL_EMAIL'); ?></th>
        <th><?php $FPCM_LANG->write('COMMMENT_CREATEDATE'); ?></th>
        <th></th>
        <th class="fpcm-th-select-row"><?php fpcm\model\view\helper::checkbox('fpcm-select-all', '', '', '', 'fpcm-select-all', false); ?></th>
    </tr>
    
    <?php \fpcm\model\view\helper::notFoundContainer($comments, 6); ?>

    <tr class="fpcm-td-spacer"><td></td></tr>
    <?php foreach($comments AS $comment) : ?>
    <tr>
        <td <?php if ($commentsMode == 1) : ?>class="fpcm-ui-articlelist-open"<?php endif; ?>>
            <?php if ($commentsMode == 1) : ?><?php \fpcm\model\view\helper::linkButton($comment->getArticleLink(), 'GLOBAL_FRONTEND_OPEN', '', 'fpcm-ui-button-blank fpcm-openlink-btn', '_blank'); ?><?php endif; ?>
            <?php \fpcm\model\view\helper::editButton($comment->getEditLink().'&mode='.$commentsMode, $comment->getEditPermission(), $commentsMode == 2 ? 'fpcm-ui-commentlist-link': ''); ?>
        </td>
        <td><strong title="<?php print substr(\fpcm\model\view\helper::escapeVal($comment->getText()), 0, 100); ?>..."><?php print \fpcm\model\view\helper::escapeVal($comment->getName()); ?></strong></td>
        <td><?php print \fpcm\model\view\helper::escapeVal($comment->getEmail()); ?></td>
        <td><?php \fpcm\model\view\helper::dateText($comment->getCreatetime()); ?></td>
        <td class="fpcm-td-commentlist-meta"><?php include __DIR__.'/metainfo.php'; ?></td>
        <td class="fpcm-td-select-row">
        <?php if ($comment->getEditPermission()) : ?>
            <?php fpcm\model\view\helper::checkbox('ids[]', 'fpcm-list-selectbox', $comment->getId(), '', 'chbx'.$comment->getId(), false); ?>
        <?php else : ?>
            <?php fpcm\model\view\helper::checkbox('ids[ro]', 'fpcm-list-selectbox', '', '', 'chbx'.$comment->getId(), false, true); ?>
        <?php endif; ?>            
        </td>
    </tr>      
    <?php endforeach; ?>
</table>

<?php include dirname(__DIR__).'/components/pager.php'; ?>

<?php if ($canEditComments || $canDelete || $commentsMode == 1) : ?>
<div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons fpcm-ui-commentaction-buttons">
    <div class="fpcm-ui-margin-center">
        <?php if ($commentsMode == 1) : ?><?php \fpcm\model\view\helper::linkButton('#', 'ARTICLES_SEARCH', 'fpcmcommentsopensearch', 'fpcm-articles-opensearch'); ?><?php endif; ?>
        <?php if ($canEditComments) : ?><?php \fpcm\model\view\helper::linkButton('#', 'GLOBAL_EDIT', 'fpcm-comments-listmassedit', 'fpcm-ui-button-massedit'); ?><?php endif; ?>
        <?php if ($canDelete) : ?><?php fpcm\model\view\helper::deleteButton('deleteComment'); ?><?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php if ($canEditComments) : ?><?php include __DIR__.'/massedit.php'; ?><?php endif; ?>