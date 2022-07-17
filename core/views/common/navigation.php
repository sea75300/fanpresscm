<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $ni fpcm\model\theme\navigationItem */ ?>
<?php if ($theView->navigation && $theView->loggedIn) : ?>
<nav class="navbar navbar-expand-md mb-auto" id="fpcm-navigation">
    <button class="navbar-toggler my-2 my-xl-0 mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-navigation-menu" aria-controls="fpcm-navigation-menu" aria-expanded="false" aria-label="<?php $theView->write('NAVIGATION_SHOW'); ?>">
        <?php $theView->icon('bars')->setClass('py-2'); ?>
    </button>

    <div class="collapse navbar-collapse" id="fpcm-navigation-menu">
        <ul class="nav nav-pills flex-column">
        <?php foreach ($theView->navigation->fetch() as $ng) : ?>
            <?php foreach ($ng as $area => $ni) : print $ni; endforeach; ?>
        <?php endforeach; ?>
        </ul>
    </div>
</nav>
<?php endif; ?>