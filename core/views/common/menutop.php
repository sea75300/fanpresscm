<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->loggedIn) : ?>
<nav class="navbar navbar-expand navbar-dark bg-primary bg-gradient ui-navigation" id="fpcm-top-menu">
    <div class="container-fluid g-0">
        
        <div class="navbar-brand px-3 me-0">
            <!-- FanPress CM News System <?php print $theView->version; ?> -->
            <div class="border-bottom border-5 border-info d-inline-block">
                <a href="<?php print $theView->basePath; ?>system/dashboard"><img src="<?php print $theView->themePath; ?>logo.svg" alt="FanPress CM News System <?php print $theView->version; ?>" class="fpcm ui-invert-1"></a>
            </div>
            <h1 class="d-none">FanPress CM News System</h1>
        </div>
        
        <div class="align-items-end">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php print $theView->frontEndLink; ?>" title="<?php $theView->write('GLOBAL_FRONTEND_OPEN'); ?>">
                        <?php $theView->icon('play')->setSize('lg')->setClass('fpcm-navicon'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="fpcm-clear-cache" href="#" class="nav-link" title="<?php $theView->write('GLOBAL_CACHE_CLEAR'); ?>">
                        <?php $theView->icon('recycle')->setSize('lg') ?>
                    </a>
                </li>
                <?php if ($theView->helpLink['ref'] !== null && $theView->helpLink['chapter'] !== null) : ?>
                <li class="nav-item">
                    <a class="nav-link fpcm ui-help-dialog" 
                        href="#"
                        title="<?php $theView->write('HELP_BTN_OPEN'); ?>"
                        data-ref="<?php print $theView->helpLink['ref']; ?>" 
                        data-chapter="<?php print $theView->helpLink['chapter']; ?>">
                        <?php $theView->icon('question-circle')->setSize('lg'); ?>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle" href="#" id="fpcm-notify-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php $theView->icon('envelope')->setClass('fpcm-navicon')->setSize('lg'); ?>                
                        <span class="d-none d-md-inline"><?php $theView->write('PROFILE_MENU_NOTIFICATIONS'); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="fpcm-notify-menu">
                        <?php print $theView->notificationString; ?>
                    </ul>
                </li>
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle" href="#" id="fpcm-profile-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php $theView->icon('user-circle')->setClass('fpcm-navicon')->setSize('lg'); ?>                
                        <span class="d-none d-md-inline"><?php $theView->write('PROFILE_MENU_LOGGEDINAS',  ['{{username}}' => $theView->currentUser->getDisplayName()]); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="fpcm-profile-menu">
                        <li class="dropdown-item fpcm-ui-font-small">
                            <b><?php $theView->write('PROFILE_MENU_LOGGEDINSINCE'); ?>:</b><br>
                            <?php $theView->dateText($theView->loginTime); ?> (<?php print $theView->dateTimeZone; ?>)
                        </li>
                        <li class="dropdown-item fpcm-ui-font-small">
                            <b><?php $theView->write('PROFILE_MENU_YOURIP'); ?></b><br>
                            <?php print $theView->ipAddress; ?>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="dropdown-item">
                            <a class="text-truncate" href="<?php print $theView->controllerLink('system/profile'); ?>">
                                <?php $theView->icon('wrench'); ?>
                                <?php $theView->write('PROFILE_MENU_OPENPROFILE'); ?>
                            </a>
                        </li>
                        <li class="dropdown-item">
                            <a href="<?php print $theView->controllerLink('system/info'); ?>" rel="license">
                                <?php $theView->icon('info-circle'); ?>
                                <?php $theView->write('HL_HELP_SUPPORT'); ?>
                            </a>
                        </li>
                        <li class="dropdown-item">
                            <a href="<?php print $theView->controllerLink('system/logout'); ?>">
                                <?php $theView->icon('sign-out-alt'); ?>
                                <?php $theView->write('LOGOUT_BTN'); ?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
 <?php endif; ?> 