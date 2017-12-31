<?php if ($FPCM_LOGGEDIN) : ?>
 <div class="fpcm-status-info">
     <ul class="fpcm-menu-top">
     <?php if ($FPCM_SHORTHELP_LINK) : ?>
         <li class="fpcm-menu-top-level1 fpcm-ui-helplink">
             <a href="<?php \fpcm\model\view\helper::printHelpLink($FPCM_SHORTHELP_LINK); ?>" title="<?php $FPCM_LANG->write('HELP_BTN_OPEN'); ?>">
                 <span class="fa fa-question-circle fa-lg fa-fw"></span>
             </a>
         </li>
     <?php endif; ?>
         <li class="fpcm-menu-top-level1" id="fpcm-ui-showmenu-li">
             <a href="#" id="fpcm-ui-showmenu">
                 <span class="fpcm-navicon fa fa-bars fa-fw fa-lg"></span>
                 <span class="fpcm-navigation-descr"><?php $FPCM_LANG->write('NAVIGATION_SHOW'); ?></span>
             </a>
         </li>
         <li class="fpcm-menu-top-level1 fpcm-ui-center" id="fpcm-navigation-profile">
             <a href="#" target="_blank" class="fpcm-navigation-noclick">
                <span class="fpcm-navicon fa fa-user fa-lg fa-fw"></span>
                <?php $FPCM_LANG->write('PROFILE_MENU_LOGGEDINAS',  array('{{username}}' => $FPCM_USER)); ?>
                <span class="fpcm-navicon fa fa-angle-down fa-lg fa-fw"></span>
             </a>
             <ul class="fpcm-submenu">
                 <li class="fpcm-menu-top-level2">
                     <a href="<?php print $FPCM_BASEMODULELINK; ?>system/profile" class="fpcm-loader">
                         <span class="fa fa-wrench fa-fw"></span>
                         <span class="fpcm-navigation-descr"><?php $FPCM_LANG->write('PROFILE_MENU_OPENPROFILE'); ?></span>
                     </a>
                 </li>
                 <li class="fpcm-menu-top-level2">
                     <a href="<?php print $FPCM_BASEMODULELINK; ?>system/logout" class="fpcm-loader">
                         <span class="fa fa-sign-out fa-fw"></span>
                         <span class="fpcm-navigation-descr"><?php $FPCM_LANG->write('LOGOUT_BTN'); ?></span>
                     </a>
                 </li>
                 <li class="fpcm-menu-top-level2 fpcm-menu-top-level2-status">
                     <span><b><?php $FPCM_LANG->write('PROFILE_MENU_LOGGEDINSINCE'); ?>:</b></span>
                     <span><?php \fpcm\model\view\helper::dateText($FPCM_SESSION_LOGIN); ?> (<?php print $FPCM_DATETIME_ZONE; ?>)</span>
                     <span><b><?php $FPCM_LANG->write('PROFILE_MENU_YOURIP'); ?></b></span>
                     <span><?php print fpcm\classes\http::getIp(); ?></span>
                 </li>
             </ul>
         </li>
         <li class="fpcm-menu-top-level1" id="fpcm-clear-cache" title="<?php $FPCM_LANG->write('GLOBAL_CACHE_CLEAR'); ?>">
             <a href="#" target="_blank">
                 <span class="fpcm-ui-center fpcm-navicon fa fa-recycle fa-lg fa-fw"></span>
             </a>
         </li>
         <li class="fpcm-menu-top-level1" title="<?php $FPCM_LANG->write('GLOBAL_FRONTEND_OPEN'); ?>">
             <a href="<?php print $FPCM_FRONTEND_LINK; ?>" target="_blank">
                 <span class="fpcm-ui-center fpcm-navicon fa fa-play fa-lg fa-fw"></span>
             </a>
         </li>
         <?php if (!empty($notificationsString)) : ?><?php print $notificationsString; ?><?php endif; ?>
     </ul>

     <div class="fpcm-clear"></div>
 </div>
 <?php endif; ?> 