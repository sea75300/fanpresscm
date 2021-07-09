<?php /* @var $theView \fpcm\view\viewVars */ ?>

<div class="row my-1">
    <div class="<?php $theView->defaultBoxHalf(); ?> pe-0 pe-md-2">
        <h3><?php print $theView->escape($revision->getTitle()); ?></h3>
    </div>
    <div class="<?php $theView->defaultBoxHalf(); ?> ps-0 ps-md-2">
        <h3><?php print $theView->escape($article->getTitle()); ?></h3>
    </div>
</div>

<div class="row my-1">

    <div class="<?php $theView->defaultBoxHalf(); ?> pe-0 pe-md-2">
        <div class="row fpcm-ui-editor-metabox">
            <?php
                $tmpArticle = $article;
                $article    = $revision;
                $createInfo = $articleCreate;
                $changeInfo = $articleChange;
            ?>
            <div class="col-sm-12 px-0">
                <?php include $theView->getIncludePath('articles/times.php'); ?>
            </div>
        </div>
    </div>
    <div class="<?php $theView->defaultBoxHalf(); ?> ps-0 ps-md-2">
        <div class="row fpcm-ui-editor-metabox">
            <?php
                $article    = $tmpArticle;
                $tmpArticle = null;
                $createInfo = $revisionCreate;
                $changeInfo = $revisionChange;
            ?>        
            <div class="col-sm-12 px-0">
                <?php include $theView->getIncludePath('articles/times.php'); ?>
            </div>
        </div>
    </div>

</div>

<div class="row my-2">
    <div class="<?php $theView->defaultBoxHalf(); ?> pe-0 pe-md-2">
        <fieldset>
            <legend><?php $theView->write('TEMPLATE_ARTICLE_CATEGORYTEXTS'); ?></legend>
            <?php print $theView->escape(implode(', ', $categoriesRevision)); ?>
        </fieldset>
    </div>
    <div class="<?php $theView->defaultBoxHalf(); ?> ps-0 ps-md-2">
        <fieldset>
            <legend><?php $theView->write('TEMPLATE_ARTICLE_CATEGORYTEXTS'); ?></legend>
            <?php print $theView->escape(implode(', ', $categoriesArticle)); ?>
        </fieldset>
    </div>
</div>

<div class="row my-2">
    <div class="<?php $theView->defaultBoxHalf(); ?> pe-0 pe-md-2">
        <fieldset>
            <legend><?php $theView->write('TEMPLATE_ARTICLE_SOURCES'); ?></legend>
            <?php print $revision->getSources() ? $revision->getSources() : '-'; ?>
        </fieldset>
    </div>
    <div class="<?php $theView->defaultBoxHalf(); ?> ps-0 ps-md-2">
        <fieldset>
            <legend><?php $theView->write('TEMPLATE_ARTICLE_SOURCES'); ?></legend>
            <?php print $article->getSources() ? $article->getSources() : '-'; ?>
        </fieldset>
    </div>
</div>

<div class="row my-2">
    <div class="<?php $theView->defaultBoxHalf(); ?> pe-0 pe-md-2">
        <fieldset>
            <legend><?php $theView->write('TEMPLATE_ARTICLE_ARTICLEIMAGE'); ?></legend>
            <?php print $revision->getImagepath() ? $revision->getImagepath() : '-'; ?>
        </fieldset>
    </div>
    <div class="<?php $theView->defaultBoxHalf(); ?> ps-0 ps-md-2">
        <fieldset>
            <legend><?php $theView->write('TEMPLATE_ARTICLE_ARTICLEIMAGE'); ?></legend>
            <?php print $article->getImagepath() ? $article->getImagepath() : '-'; ?>
        </fieldset>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12 fpcm-ui-border-radius-all fpcm-ui-ellipsis">
    <?php if (trim($diffResult)) : ?>
        <?php print $diffResult; ?>
    <?php else : ?>
        <?php print $revision->getContent(); ?>
    <?php endif; ?>
    </div>
</div>