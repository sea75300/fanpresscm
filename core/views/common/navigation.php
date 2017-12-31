<?php if (isset($FPCM_NAVIGATION) && $FPCM_LOGGEDIN) : ?>
<div class="fpcm-navigation-wrapper">
    <div class="fpcm-navigation">
        <ul id="fpcm-navigation-ul" class="fpcm-menu">
    <?php foreach ($FPCM_NAVIGATION as $navigationGroup) : ?>
        <?php foreach ($navigationGroup as $groupName => $navigationItem) : ?>     
            <li id="item<?php print $navigationItem->getId(); ?>" class="fpcm-menu-level1 fpcm-menu-level1-show fpcm-ui-center <?php if ($navigationItem->isActive()) : ?>fpcm-menu-active<?php endif; ?>">
                <a href="<?php print $navigationItem->getFullUrl(); ?>" class="<?php print $navigationItem->getClass(); ?> fpcm-loader" id="<?php print $navigationItem->getId(); ?>">
                    <span class="fpcm-ui-center fpcm-navicon <?php print $navigationItem->getIcon(); ?>"></span>
                    <span class="fpcm-ui-center fpcm-navigation-descr">
                        <?php print $navigationItem->getDescription(); ?>                    
                        <?php if ($navigationItem->hasSubmenu()) : ?>&nbsp;<span class="fa fa-angle-right"></span><?php endif; ?>
                    </span>
                </a>
                <?php if ($navigationItem->hasSubmenu()) : ?>
                    <ul class="fpcm-submenu">
                        <?php 
                        foreach ($navigationItem->getSubmenu() as $submenuItem) : ?>
                        <li id="submenu-item<?php print $submenuItem->getId(); ?>" class="fpcm-menu-level2 <?php if ($submenuItem->isActive()) : ?>fpcm-menu-active<?php endif; ?>">
                                <a href="<?php print $submenuItem->getFullUrl(); ?>" class="<?php print $submenuItem->getClass(); ?> fpcm-loader" id="<?php print $submenuItem->getId(); ?>">
                                    <?php if ($submenuItem->getIcon()) : ?><span class="<?php print $submenuItem->getIcon(); ?>"></span><?php endif; ?>
                                    <span class="fpcm-navigation-descr"><?php print $submenuItem->getDescription(); ?></span>
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

        <div class="fpcm-clear"></div>

    </div>
    
    <?php fpcmDebugOutput(); ?>
</div>
<?php endif; ?>