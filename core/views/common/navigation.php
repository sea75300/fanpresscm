<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($theView->navigation && $theView->loggedIn) : ?>
<div id="fpcm-navigation" class="fpcm ui-background-white-50p">
    <div class="greedy">        
        <ul class="fpcm-ui-menu col">
    <?php foreach ($theView->navigation as $navigationGroup) : ?>
        <?php foreach ($navigationGroup as $groupName => $navigationItem) : ?>     
            <li id="item<?php print $navigationItem->getId(); ?>" class="col-12 fpcm-menu-level1 fpcm-menu-level1-show fpcm-ui-center <?php print $navigationItem->getWrapperClass(); ?> <?php if ($navigationItem->hasSubmenu()) : ?>fpcm-menu-has-submenu<?php endif; ?> <?php if ($navigationItem->isActive()) : ?>fpcm-menu-item-active<?php endif; ?>">
                <a href="<?php print $navigationItem->getFullUrl(); ?>" class="<?php print $navigationItem->getClass(); ?> fpcm-loader" id="<?php print $navigationItem->getId(); ?>">
                    <span class="fpcm-ui-center fpcm-ui-nav-icon"><?php print $navigationItem->getIcon(); ?></span>
                    <span class="fpcm-ui-center fpcm-ui-nav-descr">
                        <?php print $navigationItem->getDescription(); ?>                    
                        <?php if ($navigationItem->hasSubmenu()) : ?>&nbsp;<?php $theView->icon('chevron-down'); ?><?php endif; ?>
                    </span>
                </a>
                <?php if ($navigationItem->hasSubmenu()) : ?>
                    <ul class="fpcm-ui-sub-menu col-12 fpcm ui-background-white-90p ui-blurring">
                        <?php 
                        foreach ($navigationItem->getSubmenu() as $submenuItem) : ?>
                        <li id="submenu-item<?php print $submenuItem->getId(); ?>" class="col-12 fpcm-menu-level2 fpcm-ui-ellipsis <?php if ($submenuItem->isActive()) : ?>fpcm-menu-item-active<?php endif; ?>">
                                <a href="<?php print $submenuItem->getFullUrl(); ?>" class="<?php print $submenuItem->getClass(); ?> fpcm-loader" id="<?php print $submenuItem->getId(); ?>">
                                    <?php if ($submenuItem->getIcon()) : ?>
                                        <span class="fpcm-ui-center fpcm-ui-nav-sub-icon"><?php print $submenuItem->getIcon(); ?></span>
                                    <?php endif; ?>
                                    <span class="fpcm-ui-nav-sub-descr"><?php print $submenuItem->getDescription(); ?></span>
                                </a>
                            </li>
                            <?php if ($submenuItem->hasSpacer()) :?>
                                <div class="fpcm-admin-nav-modmgr-link"></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>            
        <?php endforeach; ?>
    <?php endforeach; ?>
        </ul>

        <?php $theView->button('hiddenMenu')->setIcon('bars')->setSize('lg')->setText('NAVIGATION_SHOW')->setIconOnly(true)->setClass('fpcm ui-border-radius-none ui-center fpcm-ui-hidden'); ?>

        <ul class="fpcm-ui-nav-hidden-links fpcm ui-background-white-90p ui-blurring fpcm-ui-hidden fpcm-ui-position-right-0"></ul>

        <div class="fpcm-ui-clear"></div>
    </div>

</div>
<?php endif; ?>