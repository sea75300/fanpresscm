<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul class="fpcm-tabs-articles-headers">
            <li><a href="#tabs-article-list"><?php $theView->write('HL_ARTICLE_EDIT'); ?></a></li>
        </ul>

        <div id="tabs-article-list">
            <div id="fpcm-dataview-articlelist"></div>
        </div>
    </div>

    

    <?php include $theView->getIncludePath('articles/searchform.php'); ?>
    <?php if ($canEdit) : ?><?php include $theView->getIncludePath('articles/massedit.php'); ?><?php endif; ?>
</div>