<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="d-none" id="fpcm-dialog-users-select-delete">  
    <div class="row py-2">
        <?php $theView->select('articles[action]', 'articlesaction')
                ->setText('USERS_ARTICLES_SELECT')
                ->setOptions($theView->translate('USERS_ARTICLES_LIST'))
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
    </div>
 
    <div class="row py-2">
        <?php $theView->select('articles[user]', 'articlesuser')
                ->setText('USERS_ARTICLES_USER')
                ->setOptions($usersListSelect); ?>
    </div>
</div>

<div class="offcanvas offcanvas-end fpcm offcanvas-large" tabindex="-1" id="offcanvasUserStats" aria-labelledby="offcanvasUserStatsLabel" data-bs-scroll="true">
    <div class="offcanvas-header text-white bg-primary">
        <h5 class="offcanvas-title" id="offcanvasUserStatsLabel"><?php $theView->icon('chart-pie'); ?> <?php $theView->write('USERS_STATS_ARTICLE'); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php $theView->write('GLOBAL_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <?php print $userArticles; ?>

    </div>
</div>