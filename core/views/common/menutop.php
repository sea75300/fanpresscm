<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if ($theView->loggedIn) : ?>
<nav class="navbar navbar-expand navbar-dark bg-primary bg-gradient ui-navigation border-5 border-bottom border-white border-opacity-25" id="fpcm-top-menu">
    <div class="container-fluid g-0">
        
        <div class="navbar-brand ms-1 ms-md-3">
            <!-- FanPress CM News System <?php print $theView->version; ?> -->
            <div class="border-bottom border-5 border-info d-inline-block">
                <a href="<?php print $theView->basePath; ?>system/dashboard"><img src="<?php print $theView->themePath; ?>logo.svg" alt="FanPress CM News System <?php print $theView->version; ?>" class="fpcm ui-invert-1"></a>
            </div>
            <h1 class="d-none">FanPress CM News System</h1>
        </div>
        
        <div class="align-items-end">
            <ul class="navbar-nav me-auto">
                <li class="nav-item dropdown d-none d-md-inline">
                    <button id="fpcm-id-search-global-btn" class="nav-link dropdown-toggle bg-transparent border-0" title="<?php $theView->write('ARTICLES_SEARCH'); ?>" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <?php $theView->icon('magnifying-glass')->setSize('lg')->setClass('fpcm-navicon')->setSize('lg')->setText('ARTICLES_SEARCH'); ?>
                    </button>               
                    
                    <div class="dropdown-menu fpcm ui-z-index-top ui-max-width-md" aria-labelledby="fpcm-id-search-global-btn" id="fpcm-id-search-global">

                        <h6 class="dropdown-header me-5"><?php $theView->write('LABEL_SEARCH_GLOBAL'); ?></h6>

                        <div class="dropdown-item-text">
                            <div class="input-group input-group-sm w-auto">
                                <input type="text" class="form-control" id="fpcm-id-search-global-text" placeholder="<?php $theView->write('ARTICLE_SEARCH_TEXT'); ?>" aria-label="<?php $theView->write('ARTICLE_SEARCH_TEXT'); ?>">
                                <?php $theView->button('fpcm-id-search-global-start')
                                        ->overrideButtonType('outline-secondary')
                                        ->setText('ARTICLE_SEARCH_START')
                                        ->setIcon('magnifying-glass-arrow-right')
                                        ->setIconOnly(); ?>
                            </div>

                        </div>

                    </div>
                </li>                
                
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="fpcm-notify-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php $theView->icon('envelope')->setClass('fpcm-navicon')->setSize('lg'); ?>                
                        <span class="d-none d-md-inline"><?php $theView->write('PROFILE_MENU_NOTIFICATIONS'); ?></span>
                        <?php $theView->badge('notificationsCount')->setText('PROFILE_MENU_NOTIFICATIONS')->setValue(count($theView->notifications))->addPadding(-1)->setClass('rounded-pill text-bg-warning ' . ( count($theView->notifications) ? '' : 'd-none' ) ); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end fpcm ui-z-index-top ui-max-width-md" aria-labelledby="fpcm-notify-menu" id="fpcm-id-notifications">
                        <?php print $theView->notifications; ?>
                    </ul>
                </li>
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle" href="#" id="fpcm-profile-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php $theView->icon('user-circle')->setClass('fpcm-navicon')->setSize('lg'); ?>                
                        <span class="d-none d-md-inline"><?php $theView->write('PROFILE_MENU_LOGGEDINAS',  ['{{username}}' => $theView->currentUser->getDisplayName()]); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end fpcm ui-z-index-top" aria-labelledby="fpcm-profile-menu">
                        <li class="dropdown-item disabled fpcm ui-font-small ui-color-font-disabled-dark">
                            <b><?php $theView->write('PROFILE_MENU_LOGGEDINSINCE'); ?>:</b><br>
                            <?php $theView->dateText($theView->loginTime); ?> (<?php print $theView->dateTimeZone; ?>)
                        </li>
                        <li class="dropdown-item disabled fpcm ui-font-small ui-color-font-disabled-dark">
                            <b><?php $theView->write('PROFILE_MENU_YOURIP'); ?></b><br>
                            <?php print $theView->ipAddress; ?>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <?php foreach ($theView->profileMenuButtons as $value) : ?>
                            <li class="dropdown-item">
                                <?php print $value; ?>                            
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
 <?php endif; ?> 