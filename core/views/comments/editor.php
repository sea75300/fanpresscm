<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if($commentsMode === 2) : ?><?php include_once $theView->getIncludePath('common/buttons.php'); ?><?php endif; ?>
<div class="row no-gutters fpcm-ui-editor-metabox">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('GLOBAL_METADATA'); ?></legend>

            <div class="row no-gutters">                
                <div class="<?php $theView->defaultBoxHalf(); ?>">
                    <?php $theView->icon('calendar'); ?>
                    <strong><?php $theView->write('COMMMENT_CREATEDATE'); ?>:</strong>
                    <?php $theView->dateText($comment->getCreatetime()); ?><br>
                    <?php $theView->icon('clock', 'far'); ?> 
                    <?php print $changeInfo; ?><br>
                    <?php $theView->icon('globe'); ?> 
                    <strong><?php $theView->write('COMMMENT_IPADDRESS'); ?>:</strong>
                    <?php print $comment->getIpaddress(); ?>
                </div>
            </div>
            
        </fieldset>
    </div>
</div>

<div class="row my-2">
    <div class="col-12 px-0">
        <fieldset class="ml-0">
            <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

            <div class="row py-2">
                <?php $theView->textInput('comment[name]')
                        ->setText('COMMMENT_AUTHOR')
                        ->setValue($comment->getName())
                        ->setIcon('signature')
                        ->setSize('lg')
                        ->setAutoFocused(true); ?>
            </div>            
            <div class="row py-2">
                <?php $theView->textInput('comment[email]')
                        ->setText('GLOBAL_EMAIL')
                        ->setValue($comment->getEmail())
                        ->setType('email')
                        ->setIcon('at')
                        ->setSize('lg'); ?>
            </div>            
            <div class="row py-2">
                <?php $theView->textInput('comment[website]')
                        ->setText('COMMMENT_WEBSITE')
                        ->setValue($comment->getWebsite())
                        ->setType('url')
                        ->setIcon('home')
                        ->setSize('lg'); ?>
            </div>

            <div class="row py-2">
                
                <label title="<?php $theView->write('COMMMENT_STATUS'); ?>" class="fpcm-ui-field-label-general align-self-center col-12 col-sm-6 col-md-3">
                    <?php $theView->icon('exclamation-circle')->setSize('lg'); ?> <span class="fpcm-ui-label"><?php $theView->write('COMMMENT_STATUS'); ?></span>
                </label>                
    
                <div class="col-12 col-sm-6 col-md-9 align-self-center px-0">
                    
                    <div class="col-12 fpcm ui-element-min-height-md fpcm-ui-input-wrapper-inner fpcm-ui-border-grey-medium fpcm-ui-border-radius-all">
                        <?php $theView->checkbox('comment[spam]', 'spam')->setText('COMMMENT_SPAM')->setReadonly(!$theView->permissions->comment->approve)->setSelected($comment->getSpammer())->setClass('fpcm-ui-comments-status')->setLabelClass('mr-2'); ?>
                        <?php $theView->checkbox('comment[approved]', 'approved')->setText('COMMMENT_APPROVE')->setReadonly(!$theView->permissions->comment->approve)->setSelected($comment->getApproved())->setClass('fpcm-ui-comments-status')->setLabelClass('mr-2'); ?>
                        <?php $theView->checkbox('comment[private]', 'private')->setReadonly(!$theView->permissions->comment->private)->setText('COMMMENT_PRIVATE')->setSelected($comment->getPrivate())->setClass('fpcm-ui-comments-status'); ?>
                    </div>
                    
                </div>
            </div>
        </fieldset>
    </div>
</div>

<?php include \fpcm\components\components::getArticleEditor()->getCommentEditorTemplate(); ?>