<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if($commentsMode === 2) : ?><?php include_once $theView->getIncludePath('common/buttons.php'); ?><?php endif; ?>
<div class="row no-gutters fpcm-ui-editor-metabox">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('GLOBAL_METADATA'); ?></legend>

            <div class="row no-gutters">                
                <div class="col-sm-12 col-md-6">
                    <?php $theView->icon('calendar'); ?>
                    <strong><?php $theView->write('COMMMENT_CREATEDATE'); ?>:</strong>
                    <?php $theView->dateText($comment->getCreatetime()); ?><br>
                    <?php $theView->icon('clock', 'far'); ?> 
                    <?php print $changeInfo; ?><br>
                    <?php $theView->icon('globe'); ?> 
                    <strong><?php $theView->write('COMMMENT_IPADDRESS'); ?>:</strong>
                    <?php print $comment->getIpaddress(); ?>
                </div>

                <div class="col-sm-12 col-md-6 fpcm-ui-align-right align-self-center">
                    <div class="fpcm-ui-controlgroup">
                        <?php $theView->checkbox('comment[spam]', 'spam')->setText('COMMMENT_SPAM')->setReadonly(!$canApprove)->setSelected($comment->getSpammer())->setClass('fpcm-ui-comments-status'); ?>
                        <?php $theView->checkbox('comment[approved]', 'approved')->setText('COMMMENT_APPROVE')->setReadonly(!$canApprove)->setSelected($comment->getApproved())->setClass('fpcm-ui-comments-status'); ?>
                        <?php $theView->checkbox('comment[private]', 'private')->setReadonly(!$canPrivate)->setText('COMMMENT_PRIVATE')->setSelected($comment->getPrivate())->setClass('fpcm-ui-comments-status'); ?>
                    </div>
                </div>
            </div>
            
        </fieldset>
    </div>
</div>

<div class="row fpcm-ui-margin-md-top fpcm-ui-margin-md-bottom">
    <div class="col-12 fpcm-ui-padding-none-lr">
        <fieldset class="fpcm-ui-margin-none-left">
            <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

            <div class="row fpcm-ui-padding-md-tb">
                <?php $theView->textInput('comment[name]')
                        ->setWrapper(false)
                        ->setText('COMMMENT_AUTHOR')
                        ->setValue($comment->getName())
                        ->setIcon('signature')
                        ->setSize('lg')
                        ->setClass('col-6 col-md-10 fpcm-ui-field-input-nowrapper-general')
                        ->setLabelClass('col-6 col-md-2 fpcm-ui-field-label-general'); ?>
            </div>            
            <div class="row fpcm-ui-padding-md-tb">
                <?php $theView->textInput('comment[email]')
                        ->setWrapper(false)
                        ->setText('GLOBAL_EMAIL')
                        ->setValue($comment->getEmail())
                        ->setType('email')
                        ->setIcon('at')
                        ->setSize('lg')
                        ->setClass('col-6 col-md-10 fpcm-ui-field-input-nowrapper-general')
                        ->setLabelClass('col-6 col-md-2 fpcm-ui-field-label-general'); ?>
            </div>            
            <div class="row fpcm-ui-padding-md-tb">
                <?php $theView->textInput('comment[website]')
                        ->setWrapper(false)
                        ->setText('COMMMENT_WEBSITE')
                        ->setValue($comment->getWebsite())
                        ->setType('url')
                        ->setIcon('home')
                        ->setSize('lg')
                        ->setClass('col-6 col-md-10 fpcm-ui-field-input-nowrapper-general')
                        ->setLabelClass('col-6 col-md-2 fpcm-ui-field-label-general'); ?>
            </div>            
        </fieldset>
    </div>
</div>

<?php include \fpcm\components\components::getArticleEditor()->getCommentEditorTemplate(); ?>