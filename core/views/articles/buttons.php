<?php if (!$isRevision) : ?>
    <div class="fpcm-editor-fields-tabs">
        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-external-link fa-fw fa-lg"></span></div>
            <div class="fpcm-ui-editor-extended-button fpcm-ui-editor-extended-textarea"><?php fpcm\view\helper::textInput('article[sources]', '', $article->getSources(), false, 255, 'TEMPLATE_ARTICLE_SOURCES'); ?></div>
            <div class="fpcm-ui-editor-extended-col fpcm-ui-editor-extended-textarea"></div>
            <div class="fpcm-ui-clear"></div>
        </div>

        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-picture-o fa-fw fa-lg"></span></div>
            <div class="fpcm-ui-editor-extended-button fpcm-ui-editor-extended-textarea"><?php fpcm\view\helper::textInput('article[imagepath]', '', $article->getImagepath(), false, 512, 'TEMPLATE_ARTICLE_ARTICLEIMAGE'); ?></div>
            <div class="fpcm-ui-editor-extended-col fpcm-ui-editor-extended-articleimg"><?php $theView->button('insertarticleimg', 'insertarticleimg')->setText('HL_FILES_MNG')->setIcon('picture-o')->setIconOnly(true); ?></div>
            <div class="fpcm-ui-clear"></div>
        </div>
    </div>
        
    <div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-extended">
        <?php if ($showTwitter) : ?>
        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-twitter fa-fw fa-lg"></span></div>
            <div class="fpcm-ui-editor-extended-button fpcm-ui-editor-extended-col-floatsmall">
                <?php $theView->checkbox('article[tweet]')->setText('EDITOR_TWEET_ENABLED')->setSelected($article->tweetCreationEnabled()); ?>
            </div>
            <div class="fpcm-ui-editor-extended-col fpcm-ui-editor-extended-col-tweettext">
                <?php fpcm\view\helper::textInput('article[tweettxt]', '', '', false, 512, 'EDITOR_TWEET_TEXT'); ?>
            </div>
            <div class="fpcm-ui-clear"></div>
        </div>
        <p class="fpcm-ui-center"><span class="fpcm-ui-font-small"><?php print $twitterReplacements; ?></span> <?php $theView->shorthelpButton('tweetHelp')->setText('EDITOR_TWEET_TEXT_REPLACER')->setUrl($theView->basePath.'system/templates'); ?></p>
        <?php endif; ?>

        <?php if (!$editorMode || $article->getPostponed()) : ?>
        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-clock-o fa-fw fa-lg"></span></div>
            <div class="fpcm-ui-editor-extended-button"><?php $theView->checkbox('article[postponed]')->setText('EDITOR_POSTPONETO')->setSelected($article->getPostponed()); ?></div>
            <div class="fpcm-ui-editor-extended-col fpcm-ui-editor-extended-col-postponed">
                <?php fpcm\view\helper::textInput('article[postponedate]', 'fpcm-ui-datepicker', $theView->dateText($postponedTimer, 'Y-m-d'), false, 2, false, 'fpcm-ui-datepicker-inputwrapper'); ?>
                <?php fpcm\view\helper::textInput('article[postponehour]', 'fpcm-ui-spinner-hour', $theView->dateText($postponedTimer, 'H'), false, 2, false, false); ?>
                <?php fpcm\view\helper::textInput('article[postponeminute]', 'fpcm-ui-spinner-minutes', $theView->dateText($postponedTimer, 'i'), false, 2, false, false); ?>
            </div>
            <div class="fpcm-ui-clear"></div>
        </div>       
        <?php endif; ?>
        
        <?php if (!$article->getArchived()) : ?>
        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-file-text-o fa-fw fa-lg"></span></div>
            <div class="fpcm-ui-editor-extended-button"><?php $theView->checkbox('article[draft]')->setText('EDITOR_DRAFT')->setSelected($article->getDraft()); ?></div>
            <div class="fpcm-ui-editor-extended-col"></div>
            <div class="fpcm-ui-clear"></div>
        </div>

        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-thumb-tack fa-rotate-90 fa-fw fa-lg"></span></div>
            <div class="fpcm-ui-editor-extended-button"><?php $theView->checkbox('article[pinned]')->setText('EDITOR_PINNED')->setSelected($article->getPinned()); ?></div>
            <div class="fpcm-ui-editor-extended-col"></div>
            <div class="fpcm-ui-clear"></div>
        </div>
        <?php endif; ?>
        
        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-comments-o fa-fw fa-lg"></span></div>
            <div class="fpcm-ui-editor-extended-button"><?php $theView->checkbox('article[comments]')->setText('EDITOR_COMMENTS')->setSelected($article->getComments()); ?></div>
            <div class="fpcm-ui-editor-extended-col"></div>
            <div class="fpcm-ui-clear"></div>
        </div>
        
        <?php if ($editorMode) : ?>
        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-archive fa-fw fa-lg"></span></div>
            <div class="fpcm-ui-editor-extended-button"><?php $theView->checkbox('article[archived]')->setText('EDITOR_ARCHIVE')->setSelected($article->getArchived()); ?></div>
            <div class="fpcm-ui-editor-extended-col"></div>
            <div class="fpcm-ui-clear"></div>
        </div>
        <?php endif; ?>
        
        <?php if ($changeAuthor) : ?>
        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-user fa-fw fa-lg"></span></div>
            <div class="fpcm-ui-editor-extended-button"><?php fpcm\view\helper::select('article[author]', $changeuserList, $article->getCreateuser(), false, false); ?></div>
            <div class="fpcm-ui-editor-extended-col"></div>
            <div class="fpcm-ui-clear"></div>
        </div>  
        <?php endif; ?>

        <?php include $theView->getIncludePath('articles/userfields.php'); ?>

    </div>
<?php endif; ?>