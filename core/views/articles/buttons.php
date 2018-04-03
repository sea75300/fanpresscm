<?php /* @var $theView \fpcm\view\viewVars */ ?>

<?php if (!$isRevision) : ?>
<fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
    <legend><?php $theView->write('GLOBAL_EXTENDED'); ?></legend>

    <div class="row no-gutters fpcm-ui-margin-md-top fpcm-ui-margin-md-bottom">
        <div class="col-12">
            <div class="fpcm-ui-controlgroup">
            <?php if (!$article->getArchived()) : ?>
                <?php $theView->checkbox('article[pinned]')->setText('EDITOR_PINNED')->setSelected($article->getPinned()); ?>
                <?php $theView->checkbox('article[draft]')->setText('EDITOR_DRAFT')->setSelected($article->getDraft()); ?>
            <?php endif; ?>
            <?php $theView->checkbox('article[comments]')->setText('EDITOR_COMMENTS')->setSelected($article->getComments()); ?>
            <?php if (!$approvalRequired) : ?><?php $theView->checkbox('article[approval]')->setText('EDITOR_STATUS_APPROVAL')->setSelected($article->getApproval()); ?><?php endif; ?>
            <?php if ($editorMode) : ?><?php $theView->checkbox('article[archived]')->setText('EDITOR_ARCHIVE')->setSelected($article->getArchived()); ?><?php endif; ?>
            <?php if ($changeAuthor) : ?><?php $theView->select('article[author]')->setOptions($changeuserList)->setSelected($article->getCreateuser())->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?><?php endif; ?>
            </div>
        </div>
    </div>
</fieldset>

<?php if (!$editorMode || $article->getPostponed()) : ?>
<fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-lg-top">
    <legend><?php $theView->write('EDITOR_POSTPONETO'); ?></legend>


    <div class="row fpcm-ui-padding-md-tb fpcm-ui-padding-none-lr-small">
        <div class="col-12 col-md-12 col-lg-3 fpcm-ui-padding-none-left"><?php $theView->checkbox('article[postponed]')->setText('EDITOR_POSTPONETO')->setSelected($article->getPostponed()); ?></div>
        <div class="col-12 col-md-4 col-lg-3 fpcm-ui-padding-none-left">
            <?php $theView->textInput('article[postponedate]')->setClass('fpcm-ui-datepicker')->setValue($theView->dateText($postponedTimer, 'Y-m-d'))->setWrapperClass('fpcm-ui-datepicker-inputwrapper'); ?>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <?php $theView->textInput('article[postponehour]')->setClass('fpcm-ui-spinner-hour')->setValue($theView->dateText($postponedTimer, 'H'))->setWrapper(false); ?>
        </div>
        <div class="col-12 col-md-4 col-lg-3 fpcm-ui-padding-none-right">
            <?php $theView->textInput('article[postponeminute]')->setClass('fpcm-ui-spinner-minutes')->setValue($theView->dateText($postponedTimer, 'i'))->setWrapper(false); ?>
        </div>
    </div>
    <?php endif; ?>
</fieldset>

<?php if ($showTwitter) : ?>
<fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-lg-top">
    <legend><?php $theView->write('EDITOR_TWEET_ENABLED'); ?></legend>
    <div class="row no-gutters fpcm-ui-padding-md-tb fpcm-editor-dialog-fullwidth-items">
        <div class="col-12 col-md-5 col-lg-3"><?php $theView->checkbox('article[tweet]')->setText('EDITOR_TWEET_ENABLED')->setSelected($article->tweetCreationEnabled())->setClass('fpcm-ui-full-width'); ?></div>
        <div class="col-12 col-md-7 col-lg-9"><?php $theView->textInput('article[tweettxt]')->setSize(512)->setText('EDITOR_TWEET_TEXT')->setPlaceholder(true); ?></div>
    </div>
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-12 fpcm-ui-center fpcm-ui-font-small">
            <?php print $twitterReplacements; ?> <?php $theView->shorthelpButton('tweetHelp')->setText('EDITOR_TWEET_TEXT_REPLACER')->setUrl($theView->basePath.'templates/templates'); ?>
        </div>
    </div>
</fieldset>
<?php endif; ?>

<fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-lg-top">
    <legend><?php $theView->write('TEMPLATE_ARTICLE_SOURCES'); ?></legend>
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('article[sources]')->setPlaceholder(true)->setText('TEMPLATE_ARTICLE_SOURCES')->setValue($article->getSources())->setIcon('external-link-alt')->setSize('lg'); ?>
    </div>
</fieldset>

<fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-lg-top">
    <legend><?php $theView->write('TEMPLATE_ARTICLE_ARTICLEIMAGE'); ?></legend>
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('article[imagepath]')->setPlaceholder(true)->setText('TEMPLATE_ARTICLE_ARTICLEIMAGE')->setValue($article->getImagepath())->setMaxlenght(512)->setIcon('image')->setSize('lg')->setInputColWidth(10); ?>
        <div class="col-2 col-lg-1 fpcm-ui-padding-none-lr fpcm-ui-center"><?php $theView->button('insertarticleimg', 'insertarticleimg')->setText('HL_FILES_MNG')->setIcon('image')->setIconOnly(true); ?></div>
    </div>
</fieldset>

<?php endif; ?>