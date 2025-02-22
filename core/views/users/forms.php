<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="offcanvas offcanvas-end fpcm offcanvas-large <?php if (!$theView->darkMode) : ?>bg-transparent<?php endif; ?>" tabindex="-1" id="offcanvasUserStats" aria-labelledby="offcanvasUserStatsLabel" data-bs-scroll="true">
    <div class="offcanvas-header text-white fpcm ui-background-blue-75 ui-blurring">
        <h5 class="offcanvas-title" id="offcanvasUserStatsLabel"><?php $theView->icon('chart-pie'); ?> <?php $theView->write('USERS_STATS_ARTICLE'); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php $theView->write('GLOBAL_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body <?php if (!$theView->darkMode) : ?>bg-white<?php endif; ?>">
        <?php print $userArticles; ?>

    </div>
</div>