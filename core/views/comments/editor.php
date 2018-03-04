<table class="fpcm-ui-table fpcm-ui-comment-editor">
    <tr>
        <td colspan="2">
            <div class="row fpcm-ui-editor-metabox fpcm-ui-padding-md-tb">
                <div class="col-sm-12 col-md-6 fpcm-ui-font-small">
                    <strong><?php $theView->write('COMMMENT_CREATEDATE'); ?>:</strong>
                    <?php $theView->dateText($comment->getCreatetime()); ?><br>
                    <?php print $changeInfo; ?><br>
                    <strong><?php $theView->write('COMMMENT_IPADDRESS'); ?>:</strong>
                    <?php print $comment->getIpaddress(); ?>                    
                    <?php if ($ipWhoisLink) : ?>(<a href="http://www.whois.com/whois/<?php print $comment->getIpaddress(); ?>" target="_blank">Whois</a>)<?php endif; ?>
                </div>
                <div class="col-sm-12 col-md-6 fpcm-ui-align-right">
                    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $comment->getSpammer(); ?>" title="<?php $theView->write('COMMMENT_SPAM'); ?>">
                        <span class="fa fa-square fa-stack-2x"></span>        
                        <span class="fa fa-flag fa-stack-1x fa-inverse"></span>
                    </span>
                    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $comment->getApproved(); ?>" title="<?php $theView->write('COMMMENT_APPROVE'); ?>">
                        <span class="fa fa-square fa-stack-2x"></span>
                        <span class="fa fa-check-circle-o fa-stack-1x fa-inverse"></span>
                    </span>
                    <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $comment->getPrivate(); ?>" title="<?php $theView->write('COMMMENT_PRIVATE'); ?>">
                        <span class="fa fa-square fa-stack-2x"></span>
                        <span class="fa fa-eye-slash fa-stack-1x fa-inverse"></span>
                    </span>
                </div>
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
    <?php if ($canApprove || $canPrivate) : ?>
    <tr>
        <td colspan="2">
            <div class="fpcm-ui-controlgroup">
                <?php if ($canApprove) : ?>
                    <?php $theView->checkbox('comment[spam]', 'spam')->setText('COMMMENT_SPAM')->setSelected($comment->getSpammer()); ?>
                    <?php $theView->checkbox('comment[approved]', 'approved')->setText('COMMMENT_APPROVE')->setSelected($comment->getApproved()); ?>
                <?php endif; ?>
                <?php if ($canPrivate) : ?><?php $theView->checkbox('comment[private]', 'private')->setText('COMMMENT_PRIVATE')->setSelected($comment->getPrivate()); ?><?php endif; ?>
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