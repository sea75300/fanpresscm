<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $comment fpcm\model\comments\comment */ ?>
<?php if($commentsMode === 2) : ?><div class="d-none"><?php include_once $theView->getIncludePath('common/buttons.php'); ?></div><?php endif; ?>
<fieldset class="my-3">
    <div class="row row-cols-1 row-cols-lg-2 my-2">
        <div class="col">

            <div class="row">
            <?php $theView->textInput('comment[name]')
                    ->setText('COMMMENT_AUTHOR')
                    ->setValue($comment->getName())
                    ->setIcon('signature')
                    ->setSize('lg')
                    ->setAutoFocused(true); ?>
            </div>

            <div class="row">
            <?php $theView->textInput('comment[email]')
                    ->setText('GLOBAL_EMAIL')
                    ->setValue($comment->getEmail())
                    ->setType('email')
                    ->setIcon('at')
                    ->setSize('lg'); ?>
            </div>

            <div class="row">
            <?php $theView->textInput('comment[website]')
                    ->setText('COMMMENT_WEBSITE')
                    ->setValue($comment->getWebsite())
                    ->setType('url')
                    ->setIcon('home')
                    ->setSize('lg'); ?>                
            </div>

            <div class="row">
            <?php $theView->textInput('comment[ipaddr]')
                    ->setText('COMMMENT_IPADDRESS')
                    ->setValue($comment->getIpaddress())
                    ->setIcon('globe')
                    ->setSize('lg'); ?>                
            </div>

            <div class="row <?php if($commentsMode === 2 || !$showArticleIdField) : ?>d-none<?php endif; ?>">
            <?php $theView->textInput('comment[article]')
                    ->setText('COMMMENT_MOVE')
                    ->setValue($comment->getArticleid())
                    ->setMaxlenght(20)
                    ->setIcon('clipboard')
                    ->setSize('lg')
                    ->setClass('fpcm-ui-input-articleid'); ?>                
            </div>


        </div>

        <div class="col">
            <div class="row">                
                <div class="col-form-label pe-3 col-12 col-sm-6 col-md-4">
                    <?php $theView->icon('cogs')->setSize('lg'); ?> <span class="fpcm-ui-label ps-1"> <?php $theView->write('COMMMENT_STATUS'); ?></span>
                </div>

                <div class="col ps-sm-0">
                    <div class="list-group">
                        <div class="list-group-item">
                            <?php $theView->checkbox('comment[approved]', 'approved')->setText('COMMMENT_APPROVE')->setReadonly(!$theView->permissions->comment->approve)->setSelected($comment->getApproved())->setSwitch(true); ?>
                        </div>
                        <div class="list-group-item">
                            <?php $theView->checkbox('comment[spam]', 'spam')->setText('COMMMENT_SPAM')->setReadonly(!$theView->permissions->comment->approve)->setSelected($comment->getSpammer())->setSwitch(true); ?>
                        </div>
                        <div class="list-group-item">
                            <?php $theView->checkbox('comment[private]', 'private')->setReadonly(!$theView->permissions->comment->private)->setText('COMMMENT_PRIVATE')->setSelected($comment->getPrivate())->setSwitch(true); ?>
                        </div>
                    </div>
                </div>
            </div>                
        </div>
    </div>
</fieldset>

<?php include \fpcm\components\components::getArticleEditor()->getCommentEditorTemplate(); ?>

<fieldset class="my-2">
    <legend class="fpcm-ui-font-small"><?php $theView->write('GLOBAL_METADATA'); ?></legend>

    <div class="row g-0 my-2 fpcm-ui-font-small">
        <div class="col-12 col-md-6">
            
            <div class="row mb-1 row-cols-2">
                <div class="col">
                    <?php $theView->icon('calendar')->setSize('lg'); ?>
                    <strong><?php $theView->write('COMMMENT_CREATEDATE'); ?>:</strong>
                </div>
                <div class="col">
                    <?php $theView->dateText($comment->getCreatetime()); ?>
                </div>
            </div>
            
            <div class="row mb-1 row-cols-2">
                <div class="col">
                    <?php $theView->icon('clock', 'far')->setSize('lg'); ?> 
                    <strong><?php $theView->write('GLOBAL_LASTCHANGE'); ?>:</strong>
                </div>
                <div class="col">
                    <?php print $changeInfo; ?>
                </div>
            </div>
        </div>
    </div>
</fieldset>