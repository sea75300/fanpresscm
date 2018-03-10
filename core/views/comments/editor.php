<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row fpcm-ui-editor-metabox fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-6 fpcm-ui-font-small">
        <?php $theView->icon('calendar'); ?>
        <strong><?php $theView->write('COMMMENT_CREATEDATE'); ?>:</strong>
        <?php $theView->dateText($comment->getCreatetime()); ?><br>
        <?php $theView->icon('clock-o'); ?> 
        <?php print $changeInfo; ?><br>
        <?php $theView->icon('globe'); ?> 
        <strong><?php $theView->write('COMMMENT_IPADDRESS'); ?>:</strong>
        <?php print $comment->getIpaddress(); ?>                    
        <?php if ($ipWhoisLink) : ?>(<a href="http://www.whois.com/whois/<?php print $comment->getIpaddress(); ?>" target="_blank">Whois</a>)<?php endif; ?>
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-align-right">
        <?php $theView->icon('flag fa-inverse')->setStack('square')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$comment->getSpammer())->setText('COMMMENT_SPAM'); ?>
        <?php $theView->icon('check-circle-o fa-inverse')->setStack('square')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$comment->getApproved())->setText('COMMMENT_APPROVE'); ?>
        <?php $theView->icon('eye-slash fa-inverse')->setStack('square')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$comment->getPrivate())->setText('COMMMENT_PRIVATE'); ?>
    </div>
</div>

<div class="row fpcm-ui-margintop-lg">
    <div class="col-sm-12 col-md-2 fpcm-ui-padding-none-lr"><?php $theView->write('COMMMENT_AUTHOR'); ?>:</div>
    <div class="col-sm-12 col-md-10 fpcm-ui-padding-none-lr"><?php $theView->textInput('comment[name]')->setValue($comment->getName()); ?></div>
</div>

<div class="row fpcm-ui-margintop-lg">
    <div class="col-sm-12 col-md-2 fpcm-ui-padding-none-lr"><?php $theView->write('GLOBAL_EMAIL'); ?>:</div>
    <div class="col-sm-12 col-md-10 fpcm-ui-padding-none-lr"><?php $theView->textInput('comment[email]')->setValue($comment->getEmail()); ?></div>
</div>

<div class="row fpcm-ui-margintop-lg">
    <div class="col-sm-12 col-md-2 fpcm-ui-padding-none-lr"><?php $theView->write('COMMMENT_WEBSITE'); ?>:</div>
    <div class="col-sm-12 col-md-10 fpcm-ui-padding-none-lr"><?php $theView->textInput('comment[website]')->setValue($comment->getWebsite()); ?></div>
</div>

<div class="row fpcm-ui-margintop-lg">
    <div class="col-sm-12 col-md-10 fpcm-ui-padding-none-lr">
    <?php if ($canApprove || $canPrivate) : ?>
    <div class="fpcm-ui-controlgroup">
        <?php if ($canApprove) : ?>
            <?php $theView->checkbox('comment[spam]', 'spam')->setText('COMMMENT_SPAM')->setSelected($comment->getSpammer())->setClass('fpcm-ui-comments-status')->setIcon('flag'); ?>
            <?php $theView->checkbox('comment[approved]', 'approved')->setText('COMMMENT_APPROVE')->setSelected($comment->getApproved())->setClass('fpcm-ui-comments-status')->setIcon('check-circle-o'); ?>
        <?php endif; ?>
        <?php if ($canPrivate) : ?><?php $theView->checkbox('comment[private]', 'private')->setText('COMMMENT_PRIVATE')->setSelected($comment->getPrivate())->setClass('fpcm-ui-comments-status')->setIcon('eye-slash'); ?><?php endif; ?>
    </div>
    <?php endif; ?>
    
    </div>
</div>

<div class="row fpcm-ui-margintop-lg">
    <div class="col-12 fpcm-ui-padding-none-lr"><?php $theView->textarea('comment[text]', 'commenttext')->setValue(stripslashes($comment->getText()) ); ?></div>
</div>

<?php if ($commentsMode) : ?><?php $theView->saveButton('commentSave')->setClass('fpcm-ui-hidden'); ?><?php endif; ?>