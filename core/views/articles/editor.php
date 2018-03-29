<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-comments"></div>

    <div class="fpcm-tabs-general" id="fpcm-editor-tabs">
        <ul>
            <?php if ($isRevision) : ?>
            <li><a href="#tabs-article"><?php $theView->write('EDITOR_STATUS_REVISION'); ?></a></li>
            <?php else : ?>
            <li id="fpcm-editor-tabs-editorregister" data-toolbar-buttons="1"><a href="#tabs-article"><?php $theView->write('ARTICLES_EDITOR'); ?></a></li>
            <?php endif; ?>
            <?php if ($showComments && !$isRevision) : ?>
            <li data-toolbar-buttons="2" data-dataview-list="commentlist"><a href="<?php print \fpcm\classes\tools::getFullControllerLink('ajax/editor/editorlist', ['id' => $article->getId(), 'view' => 'comments']); ?>">
                <?php $theView->write('HL_ARTICLE_EDIT_COMMENTS', [ 'count' => $commentCount ]); ?>
            </a></li>
            <?php endif; ?>
            <?php if ($showRevisions) : ?>
            <li data-toolbar-buttons="3" data-dataview-list="revisionslist"><a href="<?php print \fpcm\classes\tools::getFullControllerLink('ajax/editor/editorlist', ['id' => $article->getId(), 'view' => 'revisions']); ?>">
                <?php $theView->write('HL_ARTICLE_EDIT_REVISIONS', [ 'count' => $revisionCount ]); ?>
            </a></li>
            <?php endif; ?>
        </ul>            

        <div id="tabs-article">
            <div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-filemanager"></div>            

            <?php if ($isRevision) : ?>            
                <?php include $theView->getIncludePath('articles/editors/revisiondiff.php'); ?>
            <?php else : ?>
            <div class="row fpcm-ui-padding-md-tb"><?php $theView->textInput('article[title]')->setValue($article->getTitle())->setText('ARTICLE_LIST_TITLE')->setPlaceholder(true); ?></div>

                <?php if ($editorMode) : ?>
                <div class="row fpcm-ui-editor-metabox fpcm-ui-padding-md-tb">
                    <div class="col-sm-12 col-md-6 fpcm-ui-font-small">
                        <?php include $theView->getIncludePath('articles/times.php'); ?>
                    </div>
                    <div class="col-sm-12 col-md-6 fpcm-ui-align-right">
                        <?php print implode(PHP_EOL, $article->getMetaDataStatusIcons($showDraftStatus, $commentEnabledGlobal, $showArchiveStatus)); ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row fpcm-ui-padding-md-tb fpcm-ui-editor-categories">
                    <?php $fieldname = 'article[categories][]'; ?>
                    <?php include $theView->getIncludePath('articles/categories.php'); ?>
                </div>

                <?php include \fpcm\components\components::getArticleEditor()->getEditorTemplate(); ?>
                <?php include $theView->getIncludePath('articles/buttons.php'); ?>
            <?php endif; ?>
        </div>

        
    </div>
</div>

<?php if ($showComments && !$isRevision) : ?>
    <?php include $theView->getIncludePath('comments/massedit.php'); ?>
    <!-- Shortlink layer -->  
    <div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-shortlink"></div>
<?php endif; ?>