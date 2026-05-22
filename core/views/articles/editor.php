<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ( trim($rollCodex) ) : ?>
<div class="row">
    <div class="col-12">
        <?php $theView->alert('warning')
                ->setText('<span class="fw-bold">'. $theView->translate('EDITOR_CODEX') . '</span><br>' .   nl2br($theView->escapeVal($rollCodex)))
                ->setClass('mt-3'); ?>
    </div>
</div>    
<?php endif; ?>

<div class="row">
    <div class="col <?php if (!trim($rollCodex) ) : ?>mt-3<?php endif; ?>">
        <?php $theView->textInput('article[title]')->setValue($article->getTitle())
            ->setText('ARTICLE_LIST_TITLE')
            ->setPlaceholder('ARTICLE_LIST_TITLE')
            ->setAutoFocused(true)
            ->setRequired()
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
<div class="row">
    <div class="col my-3">
        <div class="card bg-secondary-subtle">
            <div class="card-body">
                <h5 class="card-title"><?php $theView->write('GLOBAL_METADATA'); ?></h5>
                <div class="row g-0 gap-2 row-cols-1 row-cols-lg-5">
                    <div class="col">
                        <?php $theView->icon('calendar')->setSize('lg'); ?>
                        <strong><?php $theView->write('GLOBAL_AUTHOR_EDITOR'); ?>:</strong>
                    </div>
                    <div class="col">
                        <?php print $createInfo; ?>
                    </div>
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
    </div>
</div>
<?php endif; ?>

<?php if ($showComments) : ?>
    <?php include $theView->getIncludePath('comments/massedit.php'); ?>
<?php endif; ?>
