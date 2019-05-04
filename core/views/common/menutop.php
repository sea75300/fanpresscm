<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->loggedIn) : ?>
<div class="fpcm-status-info">
    <ul class="fpcm-menu-top fpcm-ui-list-style-none fpcm-ui-float-right fpcm-ui-margin-none fpcm-ui-padding-none">
        <li class="fpcm-menu-top-level1 fpcm-ui-float-right fpcm-ui-helplink">
            <a href="<?php print fpcm\classes\tools::getFullControllerLink('system/info'); ?>" title="<?php $theView->write('HL_HELP_SUPPORT'); ?>">
                <?php $theView->icon('info-circle')->setSize('lg'); ?>
            </a>
        </li>
    <?php if ($theView->helpLink) : ?>
        <li class="fpcm-menu-top-level1 fpcm-ui-float-right fpcm-ui-helplink">
            <a href="#" title="<?php $theView->write('HELP_BTN_OPEN'); ?>" class="fpcm-ui-help-dialog" data-ref="<?php print $theView->helpLink['ref']; ?>" data-chapter="<?php print $theView->helpLink['chapter']; ?>">
                <?php $theView->icon('question-circle')->setSize('lg'); ?>
            </a>
        </li>
    <?php endif; ?>
        <li class="fpcm-menu-top-level1 fpcm-ui-float-right fpcm-ui-center" id="fpcm-navigation-profile">
            <a href="#" target="_blank" class="fpcm-navigation-noclick">
               <?php $theView->icon('user-circle')->setClass('fpcm-navicon')->setSize('lg'); ?>                
               <?php $theView->write('PROFILE_MENU_LOGGEDINAS',  ['{{username}}' => $theView->currentUser->getDisplayName()]); ?>
               <?php $theView->icon('angle-down')->setClass('fpcm-navicon')->setSize('lg'); ?>
            </a>
            <ul class="fpcm-ui-sub-menu fpcm-ui-list-style-none fpcm-ui-margin-none fpcm-ui-padding-none fpcm-ui-background-white-50p">
                <li class="fpcm-menu-top-level2">
                    <a href="<?php print $theView->basePath; ?>system/profile" class="fpcm-loader fpcm-ui-full-width">
                        <?php $theView->icon('wrench'); ?>
                        <span class="fpcm-navigation-descr"><?php $theView->write('PROFILE_MENU_OPENPROFILE'); ?></span>
                    </a>
                </li>
                <li class="fpcm-menu-top-level2">
                    <a href="<?php print $theView->basePath; ?>system/logout" class="fpcm-loader fpcm-ui-full-width">
                        <?php $theView->icon('sign-out-alt'); ?>
                        <span class="fpcm-navigation-descr"><?php $theView->write('LOGOUT_BTN'); ?></span>
                    </a>
                </li>
                <li class="fpcm-menu-top-level2 fpcm-menu-top-level2-status">
                    <span><b><?php $theView->write('PROFILE_MENU_LOGGEDINSINCE'); ?>:</b></span>
                    <span><?php $theView->dateText($theView->loginTime); ?> (<?php print $theView->dateTimeZone; ?>)</span>
                    <span><b><?php $theView->write('PROFILE_MENU_YOURIP'); ?></b></span>
                    <span><?php print fpcm\classes\http::getIp(); ?></span>
                </li>
            </ul>
        </li>
        <li class="fpcm-menu-top-level1 fpcm-ui-float-right" id="fpcm-clear-cache" title="<?php $theView->write('GLOBAL_CACHE_CLEAR'); ?>">
            <a href="#" target="_blank">
                <?php $theView->icon('hdd')->setSize('lg')->setClass('fpcm-navicon'); ?>
            </a>
        </li>
        <li class="fpcm-menu-top-level1 fpcm-ui-float-right" title="<?php $theView->write('GLOBAL_FRONTEND_OPEN'); ?>">
            <a href="<?php print $theView->frontEndLink; ?>" target="_blank">
                <?php $theView->icon('play')->setSize('lg')->setClass('fpcm-navicon'); ?>
            </a>
        </li>
        <?php print $theView->notificationString; ?>
    </ul>
 </div>
 <?php endif; ?> 