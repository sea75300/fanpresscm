<?php if ($theView->loggedIn) : ?>
 <div class="fpcm-status-info">
     <ul class="fpcm-menu-top">
     <?php if ($theView->helpLink) : ?>
         <li class="fpcm-menu-top-level1 fpcm-ui-helplink">
             <a href="<?php \fpcm\view\helper::printHelpLink($theView->helpLink); ?>" title="<?php $theView->lang->write('HELP_BTN_OPEN'); ?>">
                 <span class="fa fa-question-circle fa-lg fa-fw"></span>
             </a>
         </li>
     <?php endif; ?>
         <li class="fpcm-menu-top-level1" id="fpcm-ui-showmenu-li">
             <a href="#" id="fpcm-ui-showmenu">
                 <span class="fpcm-navicon fa fa-bars fa-fw fa-lg"></span>
                 <span class="fpcm-navigation-descr"><?php $theView->lang->write('NAVIGATION_SHOW'); ?></span>
             </a>
         </li>
         <li class="fpcm-menu-top-level1 fpcm-ui-center" id="fpcm-navigation-profile">
             <a href="#" target="_blank" class="fpcm-navigation-noclick">
                <span class="fpcm-navicon fa fa-user fa-lg fa-fw"></span>
                <?php $theView->lang->write('PROFILE_MENU_LOGGEDINAS',  ['{{username}}' => $theView->currentUser->getDisplayName()]); ?>
                <span class="fpcm-navicon fa fa-angle-down fa-lg fa-fw"></span>
             </a>
             <ul class="fpcm-submenu">
                 <li class="fpcm-menu-top-level2">
                     <a href="<?php print $theView->basePath; ?>system/profile" class="fpcm-loader">
                         <span class="fa fa-wrench fa-fw"></span>
                         <span class="fpcm-navigation-descr"><?php $theView->lang->write('PROFILE_MENU_OPENPROFILE'); ?></span>
                     </a>
                 </li>
                 <li class="fpcm-menu-top-level2">
                     <a href="<?php print $theView->basePath; ?>system/logout" class="fpcm-loader">
                         <span class="fa fa-sign-out fa-fw"></span>
                         <span class="fpcm-navigation-descr"><?php $theView->lang->write('LOGOUT_BTN'); ?></span>
                     </a>
                 </li>
                 <li class="fpcm-menu-top-level2 fpcm-menu-top-level2-status">
                     <span><b><?php $theView->lang->write('PROFILE_MENU_LOGGEDINSINCE'); ?>:</b></span>
                     <span><?php \fpcm\view\helper::dateText($theView->loggedIn); ?> (<?php print $theView->dateTimeZone; ?>)</span>
                     <span><b><?php $theView->lang->write('PROFILE_MENU_YOURIP'); ?></b></span>
                     <span><?php print fpcm\classes\http::getIp(); ?></span>
                 </li>
             </ul>
         </li>
         <li class="fpcm-menu-top-level1" id="fpcm-clear-cache" title="<?php $theView->lang->write('GLOBAL_CACHE_CLEAR'); ?>">
             <a href="#" target="_blank">
                 <span class="fpcm-ui-center fpcm-navicon fa fa-recycle fa-lg fa-fw"></span>
             </a>
         </li>
         <li class="fpcm-menu-top-level1" title="<?php $theView->lang->write('GLOBAL_FRONTEND_OPEN'); ?>">
             <a href="<?php print $theView->frontEndLink; ?>" target="_blank">
                 <span class="fpcm-ui-center fpcm-navicon fa fa-play fa-lg fa-fw"></span>
             </a>
         </li>
         <?php if (!empty($notificationsString)) : ?><?php print $notificationsString; ?><?php endif; ?>
     </ul>

     <div class="fpcm-clear"></div>
 </div>
 <?php endif; ?> 