<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-trash-o"></span> <?php $theView->lang->write('ARTICLES_TRASH'); ?>
    </h1>
    <form method="post" action="<?php print $theView->self; ?>?module=<?php print $listAction; ?>">
        <div class="fpcm-tabs-general">
            <ul class="fpcm-tabs-articles-headers">
                <li><a href="#tabs-article-trash"><?php $theView->lang->write('ARTICLES_TRASH'); ?></a></li>
            </ul>

            <div id="tabs-article-trash">
                <?php include __DIR__.'/lists/trash.php'; ?>
            </div>

        </div>
        
        <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">

            <div class="fpcm-ui-margin-center">
                <?php \fpcm\view\helper::select('actions[action]', $articleActions, '', false, true, false, 'fpcm-ui-input-select-articleactions'); ?>
                <?php \fpcm\view\helper::submitButton('doAction', 'GLOBAL_OK', 'fpcm-ui-articleactions-ok fpcm-loader'); ?>
                <?php if ($deletePermissions) : ?><?php \fpcm\view\helper::submitButton('trash', 'ARTICLE_LIST_EMPTYTRASH', 'fpcm-delete-btn fpcm-loader fpcm-hidden'); ?><?php endif; ?>
            </div>

        </div>
        
        <?php \fpcm\view\helper::pageTokenField(); ?>
    </form>
</div>