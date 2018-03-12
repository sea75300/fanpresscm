<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul class="fpcm-tabs-articles-headers">
            <li><a href="#tabs-article-trash"><?php $theView->write('ARTICLES_TRASH'); ?></a></li>
        </ul>

        <div id="tabs-article-trash">
            <?php include $theView->getIncludePath('articles/lists/trash.php'); ?>
        </div>

    </div>

    
</div>