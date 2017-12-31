<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-trash-o"></span> <?php $FPCM_LANG->write('ARTICLES_TRASH'); ?>
    </h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=<?php print $listAction; ?>">
        <div class="fpcm-tabs-general">
            <ul class="fpcm-tabs-articles-headers">
                <li><a href="#tabs-article-trash"><?php $FPCM_LANG->write('ARTICLES_TRASH'); ?></a></li>
            </ul>

            <div id="tabs-article-trash">
                <?php include __DIR__.'/lists/trash.php'; ?>
            </div>

        </div>
        
        <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">

            <div class="fpcm-ui-margin-center">
                <?php \fpcm\model\view\helper::select('actions[action]', $articleActions, '', false, true, false, 'fpcm-ui-input-select-articleactions'); ?>
                <?php \fpcm\model\view\helper::submitButton('doAction', 'GLOBAL_OK', 'fpcm-ui-articleactions-ok fpcm-loader'); ?>
                <?php if ($deletePermissions) : ?><?php \fpcm\model\view\helper::submitButton('trash', 'ARTICLE_LIST_EMPTYTRASH', 'fpcm-delete-btn fpcm-loader fpcm-hidden'); ?><?php endif; ?>
            </div>

        </div>
        
        <?php \fpcm\model\view\helper::pageTokenField(); ?>
    </form>
</div>