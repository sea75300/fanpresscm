<?php /* @var $theView \fpcm\view\viewVars */ ?>

<?php if (!$isRevision) : ?>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-1 fpcm-ui-padding-none-lr"><?php $theView->icon('external-link-alt')->setSize('2x'); ?></div>
    <div class="col-sm-12 col-md-11"><?php $theView->textInput('article[sources]')->setPlaceholder(true)->setText('TEMPLATE_ARTICLE_SOURCES')->setValue($article->getSources()); ?></div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-1 fpcm-ui-padding-none-lr"><?php $theView->icon('image')->setSize('2x'); ?></div>
    <div class="col-sm-11 col-md-10"><?php $theView->textInput('article[imagepath]')->setPlaceholder(true)->setText('TEMPLATE_ARTICLE_ARTICLEIMAGE')->setValue($article->getImagepath())->setMaxlenght(512); ?></div>
    <div class="col-1"><?php $theView->button('insertarticleimg', 'insertarticleimg')->setText('HL_FILES_MNG')->setIcon('image')->setIconOnly(true); ?></div>
</div>
        
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-extended">
    <?php if ($showTwitter) : ?>
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-3"><?php $theView->checkbox('article[tweet]')->setText('EDITOR_TWEET_ENABLED')->setSelected($article->tweetCreationEnabled())->setIcon('twitter', 'fab')->setClass('fpcm-full-width'); ?></div>
        <div class="col-sm-12 col-md-9"><?php $theView->textInput('article[tweettxt]')->setSize(512)->setText('EDITOR_TWEET_TEXT')->setPlaceholder(true); ?></div>
    </div>
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-12 fpcm-ui-center fpcm-ui-font-small">
            <?php print $twitterReplacements; ?> <?php $theView->shorthelpButton('tweetHelp')->setText('EDITOR_TWEET_TEXT_REPLACER')->setUrl($theView->basePath.'templates/templates'); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!$editorMode || $article->getPostponed()) : ?>

    <div class="row fpcm-ui-padding-md-tb">
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

    <?php if (!$article->getArchived()) : ?>
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-3"><?php $theView->checkbox('article[draft]')->setText('EDITOR_DRAFT')->setSelected($article->getDraft())->setIcon('file-alt', 'far'); ?></div>
    </div>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-3"><?php $theView->checkbox('article[pinned]')->setText('EDITOR_PINNED')->setSelected($article->getPinned())->setIcon('thumbtack fa-rotate-90'); ?></div>
    </div>
    <?php endif; ?>

    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-3"><?php $theView->checkbox('article[comments]')->setText('EDITOR_COMMENTS')->setSelected($article->getComments())->setIcon('comments', 'far'); ?></div>
    </div>

    <?php if ($editorMode) : ?>
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-3"><?php $theView->checkbox('article[archived]')->setText('EDITOR_ARCHIVE')->setSelected($article->getArchived())->setIcon('archive'); ?></div>
    </div>
    <?php endif; ?>

    <?php if ($changeAuthor) : ?>
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-3"><?php $theView->select('article[author]')->setOptions($changeuserList)->setSelected($article->getCreateuser())->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?></div>
    </div>
    <?php endif; ?>

</div>
<?php endif; ?>