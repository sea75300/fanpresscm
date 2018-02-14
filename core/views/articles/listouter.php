<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul class="fpcm-tabs-articles-headers">
            <li><a href="#tabs-article-list"><?php $theView->write('HL_ARTICLE_EDIT'); ?></a></li>
        </ul>

        <div id="tabs-article-list">
            <?php include $theView->getIncludePath('articles/lists/articles.php'); ?>
        </div>

    </div>

    <?php $theView->pageTokenField('pgtkn'); ?>

    <?php include $theView->getIncludePath('articles/searchform.php'); ?>
    <?php if ($canEdit) : ?><?php include $theView->getIncludePath('articles/massedit.php'); ?><?php endif; ?>
</div>