<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->loggedIn) : ?>
<nav class="navbar navbar-expand ui-navigation" id="fpcm-top-menu">
    <div class="container-fluid">
        
        <div class="navbar-brand"></div>
        
        <div class="align-items-end">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php print $theView->frontEndLink; ?>" title="<?php $theView->write('GLOBAL_FRONTEND_OPEN'); ?>">
                        <?php $theView->icon('play')->setSize('lg')->setClass('fpcm-navicon'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="fpcm-clear-cache" class="nav-link" href="#" title="<?php $theView->write('GLOBAL_CACHE_CLEAR'); ?>">
                        <?php $theView->icon('hdd')->setSize('lg')->setClass('fpcm-navicon'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fpcm-ui-help-dialog" 
                       href="#" title="<?php $theView->write('HELP_BTN_OPEN'); ?>"
                        data-ref="<?php print $theView->helpLink['ref']; ?>" 
                        data-chapter="<?php print $theView->helpLink['chapter']; ?>">
                        <?php $theView->icon('question-circle')->setSize('lg'); ?>
                    </a>                
                </li>
                <li class="nav-item">
                    <a id="fpcm-clear-cache" class="nav-link" href="<?php print $theView->controllerLink('system/info'); ?>" title="<?php $theView->write('HL_HELP_SUPPORT'); ?>" rel="license">
                        <?php $theView->icon('info-circle')->setSize('lg'); ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="fpcm-notify-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php $theView->icon('envelope')->setClass('fpcm-navicon')->setSize('lg'); ?>                
                        <?php $theView->write('PROFILE_MENU_NOTIFICATIONS'); ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="fpcm-notify-menu">
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
                        <?php $theView->write('PROFILE_MENU_LOGGEDINAS',  ['{{username}}' => $theView->currentUser->getDisplayName()]); ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="fpcm-profile-menu">
                        <li>
                            <a class="dropdown-item" href="<?php print $theView->controllerLink('system/profile'); ?>">
                                  <?php $theView->icon('wrench'); ?>
                                  <?php $theView->write('PROFILE_MENU_OPENPROFILE'); ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php print $theView->controllerLink('system/logout'); ?>">
                                  <?php $theView->icon('sign-out-alt'); ?>
                                  <?php $theView->write('LOGOUT_BTN'); ?>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <div class="px-3 fpcm-ui-font-small">
                                <b><?php $theView->write('PROFILE_MENU_LOGGEDINSINCE'); ?>:</b>
                                <?php $theView->dateText($theView->loginTime); ?> (<?php print $theView->dateTimeZone; ?>)
                            </div>
                        </li>
                        <li>
                            <div class="px-3 fpcm-ui-font-small">
                                <b><?php $theView->write('PROFILE_MENU_YOURIP'); ?></b>
                                <?php print $theView->ipAddress; ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
 <?php endif; ?> 