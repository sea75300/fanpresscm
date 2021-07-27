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
                <div class="row">
                    <?php $theView->textInput('article[title]')->setValue($article->getTitle())->setText('ARTICLE_LIST_TITLE')->setPlaceholder(true)->setAutoFocused(true); ?>
                </div>

                <div class="row">
                    <div class="col-12 <?php if ($editorMode) : ?>col-md-8 col-lg-9<?php endif; ?> fpcm-ui-editor-categories">
                        <?php $theView->select('article[categories][]')->setIsMultiple(true)->setOptions($categories)->setSelected($article->getCategories()); ?>                    
                    </div>
                    <?php if ($editorMode) : ?>
                    <div class="col-12 col-sm-auto flex-grow-1">
                        <div class="d-flex justify-content-center justify-content-md-end fs-5">
                            <?php print implode(PHP_EOL, $article->getMetaDataStatusIcons($showDraftStatus, $commentEnabledGlobal, $showArchiveStatus)); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
            </fieldset>
        </div>
    </div>
</div>                

<?php include \fpcm\components\components::getArticleEditor()->getEditorTemplate(); ?>
    
<?php if ($editorMode) : ?>

<fieldset class="my-2">
    <legend class="fpcm-ui-font-small"><?php $theView->write('GLOBAL_METADATA'); ?></legend>

    <div class="row g-0 my-2 fpcm-ui-font-small">
        <div class="col-12 col-md-6">
            
            <div class="row mb-1">
                <div class="col-auto">
                    <?php $theView->icon('calendar')->setSize('lg'); ?>
                    <strong><?php $theView->write('GLOBAL_AUTHOR_EDITOR'); ?>:</strong>
                </div>
                <div class="col">
                    <?php print $createInfo; ?>
                </div>
            </div>
            
            <div class="row mb-1">
                <div class="col-auto">
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