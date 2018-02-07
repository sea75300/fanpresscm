<div class="fpcm-content-wrapper">
    <form method="post" action="<?php print $theView->self; ?>?module=<?php print $listAction.$listActionLimit; ?>">
        <div class="fpcm-tabs-general">
            <ul class="fpcm-tabs-articles-headers">
                <li><a href="#tabs-article-list"><?php $theView->lang->write('HL_ARTICLE_EDIT'); ?></a></li>
            </ul>

            <div id="tabs-article-list">
                <?php include $theView->getIncludePath('articles/lists/articles.php'); ?>
            </div>

        </div>
        
        <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">

            <div class="fpcm-ui-margin-center">
                <?php if ($permAdd) : ?><?php \fpcm\view\helper::linkButton($theView->self.'?module=articles/add', 'HL_ARTICLE_ADD', 'fpcm-articles-listaddnew', 'fpcm-new-btn fpcm-loader'); ?><?php endif; ?>
                <?php if ($permEdit) : ?><?php \fpcm\view\helper::linkButton('#', 'GLOBAL_EDIT', 'fpcm-articles-listmassedit', 'fpcm-ui-button-massedit'); ?><?php endif; ?>
                <?php \fpcm\view\helper::linkButton('#', 'ARTICLES_SEARCH', 'fpcm-articles-opensearch', 'fpcm-articles-opensearch'); ?>
                <?php \fpcm\view\helper::select('actions[action]', $articleActions, '', false, true, false, 'fpcm-ui-input-select-articleactions'); ?>
                <?php \fpcm\view\helper::submitButton('doAction', 'GLOBAL_OK', 'fpcm-ui-articleactions-ok fpcm-loader'); ?>
            </div>

        </div>
        
        <?php $theView->pageTokenField('pgtkn'); ?>
    </form>

    <?php include $theView->getIncludePath('articles/searchform.php'); ?>
    <?php if ($canEdit) : ?><?php include $theView->getIncludePath('articles/massedit.php'); ?><?php endif; ?>
</div>