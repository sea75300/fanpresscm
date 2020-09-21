<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-comments"></div>

    <div id="fpcm-editor-tabs">
        <ul>
            <li id="fpcm-editor-tabs-editorregister" data-toolbar-buttons="1"><a href="#tabs-article"><?php $theView->write('ARTICLES_EDITOR'); ?></a></li>
            <li id="fpcm-editor-tabs-editorextended" data-toolbar-buttons="1"><a href="#tabs-extended"><?php $theView->write('GLOBAL_EXTENDED'); ?></a></li>
            <?php if ($showComments && $commentEnabledGlobal) : ?>
            <li data-toolbar-buttons="2" data-dataview-list="commentlist"><a href="<?php print $theView->controllerLink('ajax/editor/editorlist', ['id' => $article->getId(), 'view' => 'comments']); ?>">
                <?php $theView->write('HL_ARTICLE_EDIT_COMMENTS', [ 'count' => $commentCount ]); ?>
            </a></li>
            <?php endif; ?>
            <?php if ($showRevisions) : ?>
            <li data-toolbar-buttons="3" data-dataview-list="revisionslist"><a href="<?php print $theView->controllerLink('ajax/editor/editorlist', ['id' => $article->getId(), 'view' => 'revisions']); ?>">
                <?php $theView->write('HL_ARTICLE_EDIT_REVISIONS', [ 'count' => $revisionCount ]); ?>
            </a></li>
            <?php endif; ?>
        </ul>            

        <div id="tabs-article">
            <div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-filemanager"></div>            

            <div class="row fpcm-ui-padding-md-tb">
                
                <div class="col-12 fpcm-ui-padding-none-lr">                
                    <div class="row fpcm-ui-padding-md-bottom">
                        <div class="col-12 fpcm-ui-padding-none-lr">
                            <fieldset>
                                <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>

                                    <div class="row fpcm-ui-padding-md-tb fpcm-ui-editor-categories">
                                        <div class="col-12 fpcm-ui-padding-none-lr">
                                            <?php $theView->textInput('article[title]')->setValue($article->getTitle())->setText('ARTICLE_LIST_TITLE')->setPlaceholder(true)->setWrapper(true)->setAutoFocused(true); ?>
                                        </div>
                                    </div>

                                    <div class="row fpcm-ui-padding-md-tb fpcm-ui-editor-categories">
                                        <div class="col-12 fpcm-ui-padding-none-lr">
                                            <?php $theView->select('article[categories][]')->setIsMultiple(true)->setOptions($categories)->setSelected($article->getCategories()); ?>
                                        </div>
                                    </div>
                            </fieldset>
                        </div>                    
                    </div>                    
                    <?php if ($editorMode) : ?><?php include $theView->getIncludePath('articles/times.php'); ?><?php endif; ?>
                </div>
            </div>

            <?php include \fpcm\components\components::getArticleEditor()->getEditorTemplate(); ?>
        </div>

        <div id="tabs-extended"> 
                <?php include $theView->getIncludePath('articles/buttons.php'); ?>
        </div>

    </div>
</div>

<?php if ($showComments) : ?>
    <?php include $theView->getIncludePath('comments/massedit.php'); ?>
<?php endif; ?>

<!-- Shortlink layer -->
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-shortlink"></div>