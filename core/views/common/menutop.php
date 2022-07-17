<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->loggedIn) : ?>
<nav class="navbar navbar-expand navbar-dark bg-primary bg-gradient ui-navigation" id="fpcm-top-menu">
    <div class="container-fluid">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php print $theView->frontEndLink; ?>" title="<?php $theView->write('GLOBAL_FRONTEND_OPEN'); ?>">
                    <?php $theView->icon('play')->setSize('lg')->setClass('fpcm-navicon'); ?>
                </a>
            </li>
            <li class="nav-item">                    
                <button id="fpcm-clear-cache" class="nav-link bg-transparent border-0" title="<?php $theView->write('GLOBAL_CACHE_CLEAR'); ?>">
                    <?php $theView->icon('recycle')->setSize('lg') ?>
                </button>
            </li>
            <?php if ($theView->helpLink !== null && $theView->helpLink['ref'] !== null && $theView->helpLink['chapter'] !== null) : ?>
            <li class="nav-item">
                <button id="fpcm-clear-cache"
                        class="nav-link bg-transparent border-0 fpcm ui-help-dialog"
                        title="<?php $theView->write('HELP_BTN_OPEN'); ?>"
                        data-ref="<?php print $theView->helpLink['ref']; ?>" 
                        data-chapter="<?php print $theView->helpLink['chapter']; ?>">
                    <?php $theView->icon('question-circle')->setSize('lg'); ?>
                </button>
            </li>
            <?php endif; ?>
            <li class="nav-item dropdown me-2">
                <a class="nav-link dropdown-toggle" href="#" id="fpcm-notify-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php $theView->icon('envelope')->setClass('fpcm-navicon')->setSize('lg'); ?>                
                    <span class="d-none d-md-inline"><?php $theView->write('PROFILE_MENU_NOTIFICATIONS'); ?></span>
                    <?php if (count($theView->notifications)) : ?>
                    <?php $theView->badge('notificationsCount')->setText('PROFILE_MENU_NOTIFICATIONS')->setValue(count($theView->notifications))->setClass('rounded-pill bg-info'); ?>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="fpcm-notify-menu">
                    <?php print $theView->notifications; ?>
                </ul>
            </li>
            <li class="nav-item dropdown me-2">
                <a class="nav-link dropdown-toggle" href="#" id="fpcm-profile-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php $theView->icon('user-circle')->setClass('fpcm-navicon')->setSize('lg'); ?>                
                    <span class="d-none d-md-inline"><?php $theView->write('PROFILE_MENU_LOGGEDINAS',  ['{{username}}' => $theView->currentUser->getDisplayName()]); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="fpcm-profile-menu">
                    <li class="dropdown-item fpcm-ui-font-small disabled text-dark">
                        <b><?php $theView->write('PROFILE_MENU_LOGGEDINSINCE'); ?>:</b><br>
                        <?php $theView->dateText($theView->loginTime); ?> (<?php print $theView->dateTimeZone; ?>)
                    </li>
                    <li class="dropdown-item fpcm-ui-font-small disabled text-dark">
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
    
</nav>
 <?php endif; ?> 