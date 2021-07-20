<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div>
    <?php if ( trim($rollCodex) ) : ?>
    <div class="row g-0">
        <div class="col-12 my-2">
            <fieldset>
                <legend><?php $theView->write('EDITOR_CODEX'); ?></legend>
                <p><?php print nl2br($theView->escapeVal($rollCodex)); ?></p>
            </fieldset>
        </div>
    </div>    
    <?php endif; ?>

    <div class="row g-0">
        <div class="col-12 my-2">
            <fieldset>
                <legend><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></legend>
                
                <div class="mx-2">
                    <?php $theView->textInput('article[title]')->setValue($article->getTitle())->setText('ARTICLE_LIST_TITLE')->setPlaceholder(true)->setAutoFocused(true); ?>
                </div>
                
                <div class="mx-2 fpcm-ui-editor-categories">
                    <?php $theView->select('article[categories][]')->setIsMultiple(true)->setOptions($categories)->setSelected($article->getCategories()); ?>                    
                </div>
            </fieldset>
        </div>
    </div>
</div>                

<?php include \fpcm\components\components::getArticleEditor()->getEditorTemplate(); ?>
    
<?php if ($editorMode) : ?>
    <?php include $theView->getIncludePath('articles/times.php'); ?>
<?php endif; ?>

<?php if ($showComments) : ?>
    <?php include $theView->getIncludePath('comments/massedit.php'); ?>
<?php endif; ?>