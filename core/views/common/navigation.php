<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $ni fpcm\model\theme\navigationItem */ ?>
<?php if ($theView->navigation && $theView->loggedIn) : ?>
<nav class="navbar navbar-expand-xl py-0 fpcm ui-background-white-50p ui-navigation <?php if (!$theView->buttons && !$theView->pager) : ?>border-bottom border-1 border-secondary<?php endif; ?>" id="fpcm-navigation">
    <div class="container-fluid">
        <button class="navbar-toggler my-2 my-xl-0 mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-navigation-menu" aria-controls="fpcm-navigation-menu" aria-expanded="false" aria-label="<?php $theView->write('NAVIGATION_SHOW'); ?>">
            <?php $theView->icon('bars')->setClass('py-2'); ?>
        </button>

        <div class="collapse navbar-collapse" id="fpcm-navigation-menu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <?php foreach ($theView->navigation->fetch() as $ng) : ?>
                <?php foreach ($ng as $area => $ni) : print $ni; endforeach; ?>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>