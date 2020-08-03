<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->loggedIn) : ?>
<div class="fpcm-status-info">
    <ul class="fpcm-menu-top fpcm-ui-list-style-none fpcm-ui-float-right fpcm-ui-margin-none fpcm-ui-padding-none">
        <li class="fpcm-ui-position-relative fpcm-menu-top-level1 fpcm-ui-float-right fpcm-ui-center fpcm-ui-border-radius-all m-r-2 fpcm menu-sub-animation-parent">
            <a href="#" class="fpcm-navigation-noclick">
               <?php $theView->icon('user-circle')->setClass('fpcm-navicon')->setSize('lg'); ?>                
               <?php $theView->write('PROFILE_MENU_LOGGEDINAS',  ['{{username}}' => $theView->currentUser->getDisplayName()]); ?>
               <?php $theView->icon('angle-down')->setClass('fpcm-navicon')->setSize('lg'); ?>
            </a>
            <ul class="fpcm-ui-sub-menu fpcm-ui-list-style-none m-0 p-0 fpcm-ui-position-left-0 fpcm-ui-position-right-0 fpcm ui-background-white-50p ui-blurring menu-sub-animation menu-sub-animation-active">
                <?php if ($theView->permissions->system->profile) : ?>
                <li class="fpcm-menu-top-level2 fpcm-ui-align-left py-2">
                    <a href="<?php print $theView->controllerLink('system/profile'); ?>" class="fpcm-loader fpcm-ui-full-width">
                        <?php $theView->icon('wrench'); ?>
                        <span class="fpcm-navigation-descr"><?php $theView->write('PROFILE_MENU_OPENPROFILE'); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <li class="fpcm-menu-top-level2 fpcm-ui-align-left py-2">
                    <a href="<?php print $theView->controllerLink('system/logout'); ?>" class="fpcm-loader fpcm-ui-full-width">
                        <?php $theView->icon('sign-out-alt'); ?>
                        <span class="fpcm-navigation-descr"><?php $theView->write('LOGOUT_BTN'); ?></span>
                    </a>
                </li>
                <li class="fpcm-menu-top-level2 fpcm-menu-top-level2-status fpcm-ui-align-left py-2">
                    <span><b><?php $theView->write('PROFILE_MENU_LOGGEDINSINCE'); ?>:</b></span>
                    <span><?php $theView->dateText($theView->loginTime); ?> (<?php print $theView->dateTimeZone; ?>)</span>
                </li>
                <li class="fpcm-menu-top-level2 fpcm-menu-top-level2-status fpcm-ui-align-left py-2">
                    <span><b><?php $theView->write('PROFILE_MENU_YOURIP'); ?></b></span>
                    <span><?php print $theView->ipAddress; ?></span>
                </li>
            </ul>
        </li>
        <li class="fpcm-ui-position-relative fpcm-menu-top-level1 fpcm-ui-float-right fpcm-ui-center fpcm-ui-border-radius-all m-r-2 fpcm menu-sub-animation-parent">
            <a href="#" class="fpcm-navigation-noclick">
               <?php $theView->icon('envelope')->setClass('fpcm-navicon')->setSize('lg'); ?>                
               <?php $theView->write('PROFILE_MENU_NOTIFICATIONS'); ?>
               <?php $theView->icon('angle-down')->setClass('fpcm-navicon')->setSize('lg'); ?>
            </a>
            <ul class="fpcm-ui-sub-menu fpcm-ui-list-style-none m-0 p-0 fpcm-ui-position-left-0 fpcm-ui-position-right-0 fpcm ui-background-white-50p ui-blurring menu-sub-animation menu-sub-animation-active">
            <?php if ($theView->notificationString) : ?>
                <?php print $theView->notificationString; ?>
            <?php else : ?>
                <li id="fpcm-notification-itemnotfound" class="fpcm-menu-top-level2 fpcm-notification-item fpcm-ui-align-left py-2">
                    <?php $theView->icon('ban')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?>
                </li>
            <?php endif; ?>
            </ul>
        </li>        
        <li class="fpcm-menu-top-level1 fpcm-ui-float-right fpcm-ui-helplink fpcm-ui-border-radius-all">
            <a href="<?php print $theView->controllerLink('system/info'); ?>" title="<?php $theView->write('HL_HELP_SUPPORT'); ?>" rel="license">
                <?php $theView->icon('info-circle')->setSize('lg'); ?>
            </a>
        </li>
    <?php if ($theView->helpLink) : ?>
        <li class="fpcm-menu-top-level1 fpcm-ui-float-right fpcm-ui-helplink fpcm-ui-border-radius-all">
            <a href="#" title="<?php $theView->write('HELP_BTN_OPEN'); ?>" rel="help" class="fpcm-ui-help-dialog" data-ref="<?php print $theView->helpLink['ref']; ?>" data-chapter="<?php print $theView->helpLink['chapter']; ?>">
                <?php $theView->icon('question-circle')->setSize('lg'); ?>
            </a>
        </li>
    <?php endif; ?>
        <li class="fpcm-menu-top-level1 fpcm-ui-float-right fpcm-ui-border-radius-all" id="fpcm-clear-cache" title="<?php $theView->write('GLOBAL_CACHE_CLEAR'); ?>">
            <a href="#" target="_blank">
                <?php $theView->icon('hdd')->setSize('lg')->setClass('fpcm-navicon'); ?>
            </a>
        </li>
        <li class="fpcm-menu-top-level1 fpcm-ui-float-right fpcm-ui-border-radius-all" title="<?php $theView->write('GLOBAL_FRONTEND_OPEN'); ?>">
            <a href="<?php print $theView->frontEndLink; ?>" target="_blank">
                <?php $theView->icon('play')->setSize('lg')->setClass('fpcm-navicon'); ?>
            </a>
        </li>        
    </ul>
 </div>
 <?php endif; ?> 