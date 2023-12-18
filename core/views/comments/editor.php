<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $comment fpcm\model\comments\comment */ ?>
<?php if($commentsMode === 2) : ?><div class="d-none"><?php include_once $theView->getIncludePath('common/buttons.php'); ?></div><?php endif; ?>
<fieldset class="mb-3">
    <div class="row row-cols-1 row-cols-lg-2 my-2">
        <div class="col">

            <div class="row g-0">
            <?php $theView->textInput('comment[name]')
                    ->setText('COMMMENT_AUTHOR')
                    ->setPlaceholder('COMMMENT_AUTHOR')
                    ->setLabelTypeFloat()
                    ->setValue($comment->getName())
                    ->setIcon('signature')
                    ->setSize('lg')
                    ->setAutoFocused(true); ?>
            </div>

            <div class="row g-0">
            <?php $theView->textInput('comment[email]')
                    ->setText('GLOBAL_EMAIL')
                    ->setPlaceholder('GLOBAL_EMAIL')
                    ->setLabelTypeFloat()
                    ->setValue($comment->getEmail())
                    ->setType('email')
                    ->setIcon('at')
                    ->setSize('lg'); ?>
            </div>

            <div class="row g-0">
            <?php $theView->textInput('comment[website]')
                    ->setText('COMMMENT_WEBSITE')
                    ->setPlaceholder('COMMMENT_WEBSITE')
                    ->setLabelTypeFloat()
                    ->setValue($comment->getWebsite())
                    ->setType('url')
                    ->setIcon('home')
                    ->setSize('lg'); ?>                
            </div>

            <div class="row g-0">
            <?php $theView->textInput('comment[ipaddr]')
                    ->setText('COMMMENT_IPADDRESS')
                    ->setPlaceholder('COMMMENT_IPADDRESS')
                    ->setLabelTypeFloat()
                    ->setValue($comment->getIpaddress())
                    ->setIcon('network-wired')
                    ->setSize('lg'); ?>                
            </div>

            <div class="row g-0 <?php if($commentsMode === 2 || !$showArticleIdField) : ?>d-none<?php endif; ?>">
            <?php $theView->textInput('comment[article]')
                    ->setText('COMMMENT_MOVE')
                    ->setPlaceholder('COMMMENT_MOVE')
                    ->setLabelTypeFloat()
                    ->setValue($comment->getArticleid())
                    ->setMaxlenght(20)
                    ->setIcon('clipboard')
                    ->setSize('lg')
                    ->setClass('fpcm-ui-input-articleid'); ?>                
            </div>


        </div>

        <div class="col">
            <div class="list-group">
                <div class="list-group-item bg-secondary text-white" aria-label="<?php $theView->write('COMMMENT_STATUS'); ?>">
                    <?php $theView->icon('cogs')->setSize('lg'); ?> <span class="fpcm-ui-label ps-1"> <?php $theView->write('COMMMENT_STATUS'); ?>
                </div>
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
