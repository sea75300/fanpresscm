<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ( trim($rollCodex) ) : ?>
<div class="row mt-3">
    <div class="col-12">
        <?php $theView->alert('warning')->setText('<span class="fw-bold">'. $theView->translate('EDITOR_CODEX') . '</span><br>' .   nl2br($theView->escapeVal($rollCodex))); ?>
    </div>
</div>    
<?php endif; ?>

<div class="row">
    <div class="col mt-3">
        <?php $theView->textInput('article[title]')->setValue($article->getTitle())
                ->setText('ARTICLE_LIST_TITLE')
                ->setPlaceholder('ARTICLE_LIST_TITLE')
                ->setAutoFocused(true)
                ->setIcon('pen')
                ->setLabelTypeFloat(); ?>

    </div>
</div>

<div class="row row-cols-2 mb-3">
    <div class="col-12 col-sm flex-grow-1 fpcm-ui-editor-categories">
        <?php $theView->select('article[categories][]')->setIsMultiple(true)->setOptions($categories)->setSelected($article->getCategories()); ?>                    
    </div>
    <div class="col-12 col-sm-auto">
        <div class="d-flex justify-content-center justify-content-md-end fs-5">
            <?php print implode(PHP_EOL, $article->getMetaDataStatusIcons($showDraftStatus, $commentEnabledGlobal, $showArchiveStatus)); ?>
        </div>
    </div>
</div>
                

<?php include \fpcm\components\components::getArticleEditor()->getEditorTemplate(); ?>
    
<?php if ($editorMode) : ?>

<fieldset class="my-2">
    <legend class="fpcm-ui-font-small"><?php $theView->write('GLOBAL_METADATA'); ?></legend>

    <div class="row g-0 my-2 fpcm-ui-font-small">
        <div class="col-12 col-md-6">
            
            <div class="row mb-1 row-cols-2">
                <div class="col">
                    <?php $theView->icon('calendar')->setSize('lg'); ?>
                    <strong><?php $theView->write('GLOBAL_AUTHOR_EDITOR'); ?>:</strong>
                </div>
                <div class="col">
                    <?php print $createInfo; ?>
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
<?php endif; ?>

<?php if ($showComments) : ?>
    <?php include $theView->getIncludePath('comments/massedit.php'); ?>
<?php endif; ?>