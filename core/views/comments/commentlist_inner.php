<table class="fpcm-ui-table fpcm-ui-comments">
    <tr>
        <th <?php if ($commentsMode == 1) : ?>class="fpcm-ui-articlelist-open"<?php endif; ?>></th>
        <th><?php $theView->write('COMMMENT_AUTHOR'); ?></th>
        <th><?php $theView->write('GLOBAL_EMAIL'); ?></th>
        <th><?php $theView->write('COMMMENT_CREATEDATE'); ?></th>
        <th></th>
        <th class="fpcm-th-select-row"><?php fpcm\view\helper::checkbox('fpcm-select-all', '', '', '', 'fpcm-select-all', false); ?></th>
    </tr>
    
    <?php \fpcm\view\helper::notFoundContainer($comments, 6); ?>

    <tr class="fpcm-td-spacer"><td></td></tr>
    <?php foreach($comments AS $comment) : ?>
    <tr>
        <td <?php if ($commentsMode == 1) : ?>class="fpcm-ui-articlelist-open"<?php endif; ?>>
            <?php if ($commentsMode == 1) : ?><?php $theView->openButton('openBtn'.$comment->getId())->setUrlbyObject($comment)->setTarget('_blank'); ?><?php endif; ?>
            <?php $theView->editButton('editBtn'.$comment->getId())->setUrlbyObject($comment, '&mode='.$commentsMode)->setClass($commentsMode == 2 ? 'fpcm-ui-commentlist-link': ''); ?>
        </td>
        <td><strong title="<?php print substr($theView->escape($comment->getText()), 0, 100); ?>..."><?php print $theView->escape($comment->getName()); ?></strong></td>
        <td><?php print $theView->escape($comment->getEmail()); ?></td>
        <td><?php $theView->dateText($comment->getCreatetime()); ?></td>
        <td class="fpcm-td-commentlist-meta"><?php include $theView->getIncludePath('comments/metainfo.php'); ?></td>
        <td class="fpcm-td-select-row">
        <?php if ($comment->getEditPermission()) : ?>
            <?php fpcm\view\helper::checkbox('ids[]', 'fpcm-ui-list-checkbox', $comment->getId(), '', 'chbx'.$comment->getId(), false); ?>
        <?php else : ?>
            <?php fpcm\view\helper::checkbox('ids[ro]', 'fpcm-ui-list-checkbox', '', '', 'chbx'.$comment->getId(), false, true); ?>
        <?php endif; ?>            
        </td>
    </tr>      
    <?php endforeach; ?>
</table>

<?php include $theView->getIncludePath('components/pager.php'); ?>

<?php if ($canEditComments) : ?><?php include $theView->getIncludePath('comments/massedit.php'); ?><?php endif; ?>