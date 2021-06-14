<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($theView->navigation && $theView->loggedIn) : ?>
<nav class="navbar navbar-expand-xxl fpcm ui-background-white-50p ui-navigation" id="fpcm-navigation">

    <div class="container-fluid">
        
        <div class="navbar-brand">
            <img src="<?php print $theView->themePath; ?>logo.svg" alt="FanPress CM News System" title="FanPress CM News System">
            <!-- <h1 class="mx-3- mx-md-0"><?php $theView->icon('chevron-right '); ?> <span>FanPress CM</span> <span>News System</span></h1>-->
        </div>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-navigation-menu" aria-controls="fpcm-navigation-menu" aria-expanded="false" aria-label="<?php $theView->write('NAVIGATION_SHOW'); ?>">
            <?php $theView->icon('bars'); ?>
        </button>

        <div class="collapse navbar-collapse" id="fpcm-navigation-menu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php foreach ($theView->navigation->fetch() as $ng) : ?>

            <?php foreach ($ng as $groupName => $ni) : ?>   

                <li class="nav-item mx-2 <?php if ($ni->hasSubmenu()) : ?>dropdown<?php endif; ?>"  id="item<?php print $ni->getId(); ?>">
                    <a class="nav-link fpcm nav-level-1 text-center <?php print $ni->getDefaultCss(); ?>"
                       href="<?php print $ni->getFullUrl(); ?>"
                       <?php if ($ni->hasSubmenu()) : ?> role="button" data-bs-toggle="dropdown" aria-expanded="false"<?php endif; ?>
                       <?php if ($ni->isActive()) : ?>aria-current="page"<?php endif; ?>>
                        
                        <span class="d-block"><?php print $ni->getIcon(); ?></span>
                        <span class="fpcm nav-text"><?php print $ni->getDescription(); ?></span>
                    </a>
                    
                    <?php if ($ni->hasSubmenu()) : ?>
                    
                    <ul class="dropdown-menu" aria-labelledby="item<?php print $ni->getId(); ?>">
                        
                        <?php foreach ($ni->getSubmenu() as $si) : ?>
                        
                        
                        <li id="submenu-item<?php print $si->getId(); ?>">
                            <a class="dropdown-item <?php print $si->getDefaultCss(); ?>"
                               href="<?php print $si->getFullUrl(); ?>"
                               <?php if ($ni->isActive()) : ?>aria-current="true"<?php endif; ?>>
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