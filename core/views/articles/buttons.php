<?php /* @var $theView \fpcm\view\viewVars */ ?>

<?php if (!$isRevision) : ?>

<div class="row fpcm-ui-padding-md-tb">
    <div class="align-self-center col-2 col-lg-1 fpcm-ui-padding-none-lr"><?php $theView->icon('external-link-alt')->setSize('lg'); ?></div>
    <div class="col-10 col-lg-11 fpcm-ui-padding-none-lr"><?php $theView->textInput('article[sources]')->setPlaceholder(true)->setText('TEMPLATE_ARTICLE_SOURCES')->setValue($article->getSources()); ?></div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="align-self-center col-2 col-lg-1 fpcm-ui-padding-none-lr"><?php $theView->icon('image')->setSize('lg'); ?></div>
    <div class="col-8 col-lg-10 fpcm-ui-padding-none-left"><?php $theView->textInput('article[imagepath]')->setPlaceholder(true)->setText('TEMPLATE_ARTICLE_ARTICLEIMAGE')->setValue($article->getImagepath())->setMaxlenght(512); ?></div>
    <div class="col-2 col-lg-1 fpcm-ui-padding-none-lr fpcm-ui-center"><?php $theView->button('insertarticleimg', 'insertarticleimg')->setText('HL_FILES_MNG')->setIcon('image')->setIconOnly(true); ?></div>
</div>
        
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-extended">
    <?php if ($showTwitter) : ?>
    <div class="row fpcm-ui-padding-md-tb fpcm-editor-dialog-fullwidth-items">
        <div class="col-sm-12 col-md-3"><?php $theView->checkbox('article[tweet]')->setText('EDITOR_TWEET_ENABLED')->setSelected($article->tweetCreationEnabled())->setIcon('twitter', 'fab')->setClass('fpcm-ui-full-width'); ?></div>
        <div class="col-sm-12 col-md-9"><?php $theView->textInput('article[tweettxt]')->setSize(512)->setText('EDITOR_TWEET_TEXT')->setPlaceholder(true); ?></div>
    </div>
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-12 fpcm-ui-center fpcm-ui-font-small">
            <?php print $twitterReplacements; ?> <?php $theView->shorthelpButton('tweetHelp')->setText('EDITOR_TWEET_TEXT_REPLACER')->setUrl($theView->basePath.'templates/templates'); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!$editorMode || $article->getPostponed()) : ?>

    <div class="row fpcm-ui-padding-md-tb fpcm-editor-dialog-fullwidth-items">
        <div class="col-sm-12 col-md-3"><?php $theView->checkbox('article[postponed]')->setText('EDITOR_POSTPONETO')->setSelected($article->getPostponed())->setIcon('calendar-plus'); ?></div>
        <div class="col-sm-12 col-md-3">
            <?php $theView->textInput('article[postponedate]')->setClass('fpcm-ui-datepicker')->setValue($theView->dateText($postponedTimer, 'Y-m-d'))->setWrapperClass('fpcm-ui-datepicker-inputwrapper'); ?>
        </div>
        <div class="col-sm-12 col-md-3">
            <?php $theView->textInput('article[postponehour]')->setClass('fpcm-ui-spinner-hour')->setValue($theView->dateText($postponedTimer, 'H'))->setWrapper(false); ?>
        </div>
        <div class="col-sm-12 col-md-3">
            <?php $theView->textInput('article[postponeminute]')->setClass('fpcm-ui-spinner-minutes')->setValue($theView->dateText($postponedTimer, 'i'))->setWrapper(false); ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="row fpcm-ui-margintop-lg fpcm-ui-marginbottom-md">
        <div class="col-12">
            <div class="fpcm-ui-controlgroup">
            <?php if (!$article->getArchived()) : ?>
                <?php $theView->checkbox('article[draft]')->setText('EDITOR_DRAFT')->setSelected($article->getDraft())->setIcon('file-alt', 'far'); ?>
                <?php $theView->checkbox('article[pinned]')->setText('EDITOR_PINNED')->setSelected($article->getPinned())->setIcon('thumbtack fa-rotate-90'); ?>
            <?php endif; ?>
            <?php $theView->checkbox('article[comments]')->setText('EDITOR_COMMENTS')->setSelected($article->getComments())->setIcon('comments', 'far'); ?>
            <?php if ($editorMode) : ?><?php $theView->checkbox('article[archived]')->setText('EDITOR_ARCHIVE')->setSelected($article->getArchived())->setIcon('archive'); ?><?php endif; ?>
            <?php if ($changeAuthor) : ?><?php $theView->select('article[author]')->setOptions($changeuserList)->setSelected($article->getCreateuser())->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?><?php endif; ?>
            </div>
        </div>
    </div>

</div>
<?php endif; ?>