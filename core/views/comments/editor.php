<table class="fpcm-ui-table fpcm-ui-comment-editor">
    <tr>
        <td colspan="2">
            <div class="fpcm-ui-editor-metabox">
                <div class="fpcm-ui-editor-metabox-left">
                    <strong><?php $theView->write('COMMMENT_CREATEDATE'); ?>:</strong>
                    <?php $theView->dateText($comment->getCreatetime()); ?><br>
                    <?php print $changeInfo; ?><br>
                    <strong><?php $theView->write('COMMMENT_IPADDRESS'); ?>:</strong>
                    <?php print $comment->getIpaddress(); ?>                    
                    <?php if ($ipWhoisLink) : ?>(<a href="http://www.whois.com/whois/<?php print $comment->getIpaddress(); ?>" target="_blank">Whois</a>)<?php endif; ?>
                </div>                
                <?php include $theView->getIncludePath('comments/metainfo.php'); ?>
                <div class="fpcm-clear"></div>
            </div>
        </td>
    </tr>
    <tr>
        <td><strong><?php $theView->write('COMMMENT_AUTHOR'); ?></strong>:</td>
        <td><?php \fpcm\view\helper::textInput('comment[name]', 'fpcm-full-width', $comment->getName()); ?></td>
    </tr> 
    <tr>
        <td><strong><?php $theView->write('GLOBAL_EMAIL'); ?></strong>:</td>
        <td><?php \fpcm\view\helper::textInput('comment[email]', 'fpcm-full-width', $comment->getEmail()); ?></td>
    </tr> 
    <tr>
        <td><strong><?php $theView->write('COMMMENT_WEBSITE'); ?></strong>:</td>
        <td><?php \fpcm\view\helper::textInput('comment[website]', 'fpcm-full-width', $comment->getWebsite()); ?></td>
    </tr>
    <?php if ($permApprove || $permPrivate) : ?>
    <tr>
        <td colspan="2">
            <div class="fpcm-ui-controlgroup">
                <?php if ($permApprove) : ?>
                    <?php $theView->checkbox('comment[spam]', 'spam')->setText('COMMMENT_SPAM')->setSelected($comment->getSpammer()); ?>
                    <?php $theView->checkbox('comment[approved]', 'approved')->setText('COMMMENT_APPROVE')->setSelected($comment->getApproved()); ?>
                <?php endif; ?>
                <?php if ($permPrivate) : ?><?php $theView->checkbox('comment[private]', 'private')->setText('COMMMENT_PRIVATE')->setSelected($comment->getPrivate()); ?><?php endif; ?>
            </div>
        </td>
    </tr>
    <?php endif; ?>
    <tr>
        <td colspan="2"><strong><?php $theView->write('COMMMENT_TEXT'); ?></strong>:</td>
    </tr>
    <tr>
        <td colspan="2"><?php \fpcm\view\helper::textArea('comment[text]', 'fpcm-full-width', stripslashes($comment->getText()), false, false); ?></td>
    </tr>
</table>

<?php if ($commentsMode) : ?><?php $theView->saveButton('commentSave')->setClass('fpcm-ui-hidden'); ?><?php endif; ?>