<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->loggedIn) : ?>
<nav class="navbar navbar-expand navbar-dark bg-dark ui-navigation" id="fpcm-top-menu">
    <div class="container-fluid g-0">
        
        <div class="navbar-brand px-3 me-0">
            <!-- FanPress CM News System <?php print $theView->version; ?> -->
            <div class="border-bottom border-5 border-primary d-inline-block">
                <img src="<?php print $theView->themePath; ?>logo.svg" role="presentation" alt="FanPress CM News System <?php print $theView->version; ?>" class="fpcm ui-invert-1">
            </div>
            <h1 class="d-none">FanPress CM News System</h1>
        </div>
        
        <div class="align-items-end">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php print $theView->frontEndLink; ?>" title="<?php $theView->write('GLOBAL_FRONTEND_OPEN'); ?>">
                        <?php $theView->icon('play')->setSize('lg')->setClass('fpcm-navicon'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a  id="fpcm-clear-cache"
                        href="#"
                        class="nav-link" 
                        title="<?php $theView->write('GLOBAL_CACHE_CLEAR'); ?>"
                        data-fn="system.clearCache">
                        <?php $theView->icon('hdd')->setSize('lg') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fpcm-ui-help-dialog" 
                        id="fpcm-show-help"
                        href="#" title="<?php $theView->write('HELP_BTN_OPEN'); ?>"
                        data-ref="<?php print $theView->helpLink['ref']; ?>" 
                        data-chapter="<?php print $theView->helpLink['chapter']; ?>">
                        <?php $theView->icon('question-circle')->setSize('lg'); ?>
                    </a>                
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="fpcm-notify-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php $theView->icon('envelope')->setClass('fpcm-navicon')->setSize('lg'); ?>                
                        <span class="d-none d-md-inline"><?php $theView->write('PROFILE_MENU_NOTIFICATIONS'); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="fpcm-notify-menu">
                        <?php if ($theView->notificationString) : ?>
                            <?php print $theView->notificationString; ?>
                        <?php else : ?>
                            <li>
                                <?php $theView->icon('ban')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?>
                            </li>
                        <?php endif; ?>                        
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="fpcm-profile-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php $theView->icon('user-circle')->setClass('fpcm-navicon')->setSize('lg'); ?>                
                        <span class="d-none d-md-inline"><?php $theView->write('PROFILE_MENU_LOGGEDINAS',  ['{{username}}' => $theView->currentUser->getDisplayName()]); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="fpcm-profile-menu">
                        <li>
                            <div class="px-3 fpcm-ui-font-small">
                                <b><?php $theView->write('PROFILE_MENU_LOGGEDINSINCE'); ?>:</b><br>
                                <?php $theView->dateText($theView->loginTime); ?> (<?php print $theView->dateTimeZone; ?>)
                            </div>
                        </li>
                        <li>
                            <div class="px-3 fpcm-ui-font-small">
                                <b><?php $theView->write('PROFILE_MENU_YOURIP'); ?></b><br>
                                <?php print $theView->ipAddress; ?>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item  text-truncate" href="<?php print $theView->controllerLink('system/profile'); ?>">
                                <?php $theView->icon('wrench'); ?>
                                <?php $theView->write('PROFILE_MENU_OPENPROFILE'); ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php print $theView->controllerLink('system/info'); ?>" rel="license">
                                <?php $theView->icon('info-circle'); ?>
                                <?php $theView->write('HL_HELP_SUPPORT'); ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php print $theView->controllerLink('system/logout'); ?>">
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