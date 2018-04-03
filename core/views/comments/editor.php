<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row fpcm-ui-editor-metabox fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-6 fpcm-ui-font-small">
        <?php $theView->icon('calendar'); ?>
        <strong><?php $theView->write('COMMMENT_CREATEDATE'); ?>:</strong>
        <?php $theView->dateText($comment->getCreatetime()); ?><br>
        <?php $theView->icon('clock', 'far'); ?> 
        <?php print $changeInfo; ?><br>
        <?php $theView->icon('globe'); ?> 
        <strong><?php $theView->write('COMMMENT_IPADDRESS'); ?>:</strong>
        <?php print $comment->getIpaddress(); ?>                    
        <?php if ($ipWhoisLink) : ?>(<a href="http://www.whois.com/whois/<?php print $comment->getIpaddress(); ?>" target="_blank">Whois</a>)<?php endif; ?>
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-align-right"><?php print implode(PHP_EOL, $comment->getMetaDataStatusIcons()); ?></div>
</div>

<div class="row fpcm-ui-margin-md-top fpcm-ui-margin-md-bottom">
    <div class="col-12 fpcm-ui-padding-none-lr">
        <fieldset class="fpcm-ui-margin-none-left">
            <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

            <div class="row fpcm-ui-margin-md-top">
                <div class="align-self-center col-sm-12 col-md-2 fpcm-ui-padding-none-lr"><?php $theView->write('COMMMENT_AUTHOR'); ?>:</div>
                <div class="col-sm-12 col-md-10 fpcm-ui-padding-none-lr"><?php $theView->textInput('comment[name]')->setValue($comment->getName()); ?></div>
            </div>

            <div class="row fpcm-ui-margin-lg-top">
                <div class="align-self-center col-sm-12 col-md-2 fpcm-ui-padding-none-lr"><?php $theView->write('GLOBAL_EMAIL'); ?>:</div>
                <div class="col-sm-12 col-md-10 fpcm-ui-padding-none-lr"><?php $theView->textInput('comment[email]')->setValue($comment->getEmail()); ?></div>
            </div>

            <div class="row fpcm-ui-margin-lg-top">
                <div class="align-self-center col-sm-12 col-md-2 fpcm-ui-padding-none-lr"><?php $theView->write('COMMMENT_WEBSITE'); ?>:</div>
                <div class="col-sm-12 col-md-10 fpcm-ui-padding-none-lr"><?php $theView->textInput('comment[website]')->setValue($comment->getWebsite()); ?></div>
            </div>
        </fieldset>
    </div>
</div>


<?php if ($canApprove || $canPrivate) : ?>
<div class="row fpcm-ui-margin-MD-top fpcm-ui-margin-md-bottom">
    <div class="col-12 fpcm-ui-padding-none-lr">
        <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
            <legend><?php $theView->write('GLOBAL_EXTENDED'); ?></legend>

            <div class="row no-gutters fpcm-ui-margin-md-top fpcm-ui-margin-md-bottom">
                <div class="col-12">
                    <div class="fpcm-ui-controlgroup">
                        <?php if ($canApprove) : ?>
                            <?php $theView->checkbox('comment[spam]', 'spam')->setText('COMMMENT_SPAM')->setSelected($comment->getSpammer())->setClass('fpcm-ui-comments-status'); ?>
                            <?php $theView->checkbox('comment[approved]', 'approved')->setText('COMMMENT_APPROVE')->setSelected($comment->getApproved())->setClass('fpcm-ui-comments-status'); ?>
                        <?php endif; ?>
                        <?php if ($canPrivate) : ?><?php $theView->checkbox('comment[private]', 'private')->setText('COMMMENT_PRIVATE')->setSelected($comment->getPrivate())->setClass('fpcm-ui-comments-status'); ?><?php endif; ?>                
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<?php endif; ?>

<?php include \fpcm\components\components::getArticleEditor()->getCommentEditorTemplate(); ?>

<?php if ($commentsMode) : ?><?php $theView->saveButton('commentSave')->setClass('fpcm-ui-hidden'); ?><?php endif; ?>