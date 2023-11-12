<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="d-none" id="fpcm-dialog-users-select-delete">  
    <div class="row py-2">
        <div class="col">
            <?php $theView->select('articles[action]', 'articlesaction')
                    ->setText('USERS_ARTICLES_SELECT')
                    ->setOptions($theView->translate('USERS_ARTICLES_LIST'))
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat(); ?>
        </div>
    </div>
 
    <div class="row py-2">
        <div class="col">
            <?php $theView->select('articles[user]', 'articlesuser')
                    ->setText('USERS_ARTICLES_USER')
                    ->setOptions($usersListSelect)
                    ->setLabelTypeFloat(); ?>            
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end fpcm offcanvas-large <?php if (!$theView->darkMode) : ?>bg-transparent<?php endif; ?>" tabindex="-1" id="offcanvasUserStats" aria-labelledby="offcanvasUserStatsLabel" data-bs-scroll="true">
    <div class="offcanvas-header text-white fpcm ui-background-blue-75 ui-blurring">
        <h5 class="offcanvas-title" id="offcanvasUserStatsLabel"><?php $theView->icon('chart-pie'); ?> <?php $theView->write('USERS_STATS_ARTICLE'); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php $theView->write('GLOBAL_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body <?php if (!$theView->darkMode) : ?>bg-white<?php endif; ?>">
        <?php print $userArticles; ?>

    </div>
</div>