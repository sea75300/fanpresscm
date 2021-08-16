<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($theView->navigation && $theView->loggedIn) : ?>
<nav class="navbar navbar-expand-xxl py-0 fpcm ui-background-white-50p ui-navigation" id="fpcm-navigation">

    <div class="container-fluid">
        
        <div class="navbar-brand">
            <!-- FanPress CM News System <?php print $theView->version; ?> -->                
            <img src="<?php print $theView->themePath; ?>logo.svg" role="presentation" alt="FanPress CM News System <?php print $theView->version; ?>">
            <h1 class="d-none">FanPress CM News System</h1>
        </div>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-navigation-menu" aria-controls="fpcm-navigation-menu" aria-expanded="false" aria-label="<?php $theView->write('NAVIGATION_SHOW'); ?>">
            <?php $theView->icon('bars')->setClass('py-2'); ?>
        </button>

        <div class="collapse navbar-collapse" id="fpcm-navigation-menu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php foreach ($theView->navigation->fetch() as $ng) : ?>

            <?php foreach ($ng as $area => $ni) : ?>   

                <li class="nav-item <?php if ($ni->hasSubmenu()) : ?>dropdown<?php endif; ?>"  id="<?php print $ni->getId(); ?>">
                    <a class="nav-link text-center p-3 fpcm ui-nav-link <?php print $ni->getDefaultCss($theView->navigationActiveModule); ?>"
                       href="<?php print $ni->getFullUrl(); ?>"
                       <?php if ($ni->hasSubmenu()) : ?> role="button" data-bs-toggle="dropdown" aria-expanded="false"<?php endif; ?>
                       <?php if ($ni->isActive($theView->navigationActiveModule)) : ?>aria-current="page"<?php endif; ?>>
                        
                        <span class="d-block"><?php print $ni->getIcon(); ?></span>
                        <span class="fpcm nav-text"><?php print $ni->getDescription(); ?></span>
                    </a>
                    
                    <?php if ($ni->hasSubmenu()) : ?>
                    
                    <ul class="dropdown-menu shadow fpcm ui-blurring" aria-labelledby="item<?php print $ni->getId(); ?>">
                        
                        <?php foreach ($ni->getSubmenu() as $si) : ?>
                        <li id="<?php print $si->getId(); ?>">
                            <a class="dropdown-item nav-link <?php print $si->getDefaultCss($theView->navigationActiveModule); ?>"
                               href="<?php print $si->getFullUrl(); ?>"
                               <?php if ($ni->isActive($theView->navigationActiveModule)) : ?>aria-current="true"<?php endif; ?>>
                                <?php print $si->getIcon(); ?>
                                <?php print $si->getDescription(); ?>
                            </a>
                        </li>
                        <?php if ($si->hasSpacer()) :?>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>

                        <?php endforeach; ?>
                        
                    </ul>
                    <?php endif; ?>
                </li>

            <?php endforeach; ?>

        <?php endforeach; ?>

            </ul>

        </div>
    </div>

</nav>
<?php endif; ?>