<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\theme;

    /**
     * ACP navigation Objekt
     * 
     * @author Stefan Seehafer aka imagine <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\theme
     */
    class navigation extends \fpcm\model\abstracts\staticModel {

        /**
         * Permissions-Objekt
         * @var \fpcm\model\system\permissions
         */
        private $permissions;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
            
            $this->cache = new \fpcm\classes\cache('navigation_'.$this->session->getUserId(), 'theme');
        }

        /**
         * Navigation rendern
         * @return array
         */
        public function render() {
            
            if (!$this->cache->isExpired()) {
                return $this->cache->read();
            }
            
            $this->permissions = new \fpcm\model\system\permissions($this->session->getCurrentUser()->getRoll());

            $navigation = $this->getNavigation();
            $navigation = $this->events->runEvent('navigationRender', $navigation);

            foreach ($navigation as &$moduleOptions) {
                $moduleOptions = $this->checkPermissions($moduleOptions);            
            }

            $this->cache->write($navigation, $this->config->system_cache_timeout);

            return $navigation;
        }

        /**
         * Berechtigungen für Zugriff auf Module prüfen
         * @param array $navigation
         * @return array
         */
        private function checkPermissions($navigation) {

            /* @var $value navigationItem */
            foreach ($navigation as $key => &$value) {

                if (is_array($value)) {
                    trigger_error('Using an array as navigation item is deprecated as of FPCM 3.5. Create an instance of "\fpcm\model\theme\navigationItem" instead.'.PHP_EOL.print_r($value, true));
                    $value = navigationItem::createItemFromArray($value);
                }
                
                if ($value->hasSubmenu()) {
                    $value->setSubmenu($this->checkPermissions($value->getSubmenu()));
                }

                if ($value->hasPermission()) {

                    if ($this->permissions->check($value->getPermission())) {
                        continue;
                    }
                    
                    unset($navigation[$key]);
                }

            }

            return $navigation;
        }

        /**
         * Baut Navigation auf
         * @return array
         */
        private function getNavigation() {

            $navigationArray = array(
                'dashboard'      => array(
                    navigationItem::createItemFromArray([
                        'url'               => 'system/dashboard',
                        'description'       => $this->language->translate('HL_DASHBOARD'),
                        'icon'              => 'fa fa-home',
                    ])
                ),
                'addnews'      => array(
                    navigationItem::createItemFromArray([
                        'url'               => 'articles/add',
                        'permission'        => array('article' => 'add'),
                        'description'       => $this->language->translate('HL_ARTICLE_ADD'),
                        'icon'              => 'fa fa-pencil',
                        'class'             => '',
                        'id'                => ''                        
                    ])
                ),
                'editnews'      => array(
                    navigationItem::createItemFromArray([
                        'url'               => '#',
                        'permission'        => array('article' => 'edit'),
                        'description'       => $this->language->translate('HL_ARTICLE_EDIT'),
                        'icon'              => 'fa fa-book',
                        'submenu'           => self::editorSubmenu(),
                        'class'             => 'fpcm-navigation-noclick',
                        'id'                => 'nav-id-editnews'                        
                    ])
                ),
                'comments'   => array(
                    navigationItem::createItemFromArray([
                        'url'               => 'comments/list',
                        'permission'        => array('article' => array('editall', 'edit'), 'comment' => array('editall', 'edit')),
                        'description'       => $this->language->translate('HL_COMMENTS_MNG'),
                        'icon'              => 'fa fa-comments',
                        'class'             => '',
                        'id'                => 'nav-item-editcomments'                        
                    ])
                ),
                'filemanager'   => array(
                    navigationItem::createItemFromArray([
                        'url'               => 'files/list&mode=1',
                        'permission'        => array('uploads' => 'visible'),
                        'description'       => $this->language->translate('HL_FILES_MNG'),
                        'icon'              => 'fa fa-folder-open',
                        'class'             => '',
                        'id'                => ''                        
                    ])
                ),
                'options'       => array(
                    navigationItem::createItemFromArray([
                        'url'               => '#',
                        'permission'        => array('system' => 'options'),
                        'description'       => $this->language->translate('HL_OPTIONS'),
                        'icon'              => 'fa fa-cog',
                        'class'             => 'fpcm-navigation-noclick',
                        'id'                => 'fpcm-options-submenu',
                        'submenu'           => $this->optionSubmenu()
                    ])
                ),
                'modules'       => array(
                    navigationItem::createItemFromArray([
                        'url'               => '#',
                        'permission'        => array('system' => 'options', 'modules' => 'configure'),
                        'description'       => $this->language->translate('HL_MODULES'),
                        'icon'              => 'fa fa-plug',
                        'class'             => 'fpcm-navigation-noclick',
                        'id'                => '',
                        'submenu'           => $this->modulesSubmenu()                     
                    ])
                ),
                'help'          => array(
                    navigationItem::createItemFromArray([
                        'url'               => 'system/help',
                        'description'       => $this->language->translate('HL_HELP'),
                        'icon'              => 'fa fa-question-circle',
                        'class'             => '',
                        'id'                => ''                        
                    ])
                ),
                'after'         => []
            );

            $eventResult = $this->events->runEvent('navigationAdd', $navigationArray);
            if (!$eventResult) return $navigationArray;

            return array_merge($navigationArray, $eventResult);

        }

        /**
         * Erzeugt Submenü für News bearbeiten
         * @return array
         */
        private function editorSubmenu() {

            $menu = array(
                navigationItem::createItemFromArray([
                    'url'               => 'articles/listall',
                    'permission'        => array('article' => 'edit', 'article' => 'editall'),
                    'description'       => $this->language->translate('HL_ARTICLE_EDIT_ALL'),
                    'class'             => '',
                    'id'                => '',
                    'icon'              => 'fa fa-book fa-fw'
                ]),
                navigationItem::createItemFromArray([
                    'url'               => 'articles/listactive',
                    'permission'        => array('article' => 'edit'),
                    'description'       => $this->language->translate('HL_ARTICLE_EDIT_ACTIVE'),
                    'class'             => '',
                    'id'                => '',
                    'icon'              => 'fa fa-newspaper-o fa-fw'
                ]),
                navigationItem::createItemFromArray([
                    'url'               => 'articles/listarchive',
                    'permission'        => array('article' => 'edit', 'article' => 'editall', 'article' => 'archive'),
                    'description'       => $this->language->translate('HL_ARTICLE_EDIT_ARCHIVE'),
                    'class'             => '',
                    'id'                => '',
                    'icon'              => 'fa fa-archive fa-fw'
                ])                
            );
            
            if ($this->config->articles_trash) {
                $menu[] = navigationItem::createItemFromArray([
                    'url'               => 'articles/trash',
                    'permission'        => array('article' => 'delete'),
                    'description'       => $this->language->translate('ARTICLES_TRASH'),
                    'class'             => '',
                    'id'                => '',
                    'icon'              => 'fa fa-trash-o fa-fw'
                ]);
            }
            
            return $menu;
        }

        /**
         * Erzeugt Optionen-Submenü
         * @return array
         */
        private function optionSubmenu() {
            $data = array(
                navigationItem::createItemFromArray([
                    'url'               => 'system/options',
                    'permission'        => array('system' => 'options'),
                    'description'       => $this->language->translate('HL_OPTIONS_SYSTEM'),
                    'class'             => '',
                    'id'                => '',
                    'icon'              => 'fa fa-cog fa-fw'
                ]),
                navigationItem::createItemFromArray([
                    'url'               => 'users/list',
                    'permission'        => array('system' => 'users', 'system' => 'rolls'),
                    'description'       => $this->language->translate('HL_OPTIONS_USERS'),
                    'class'             => '',
                    'id'                => 'nav-item-users',
                    'icon'              => 'fa fa-users fa-fw'
                ]),
                navigationItem::createItemFromArray([
                    'url'               => 'ips/list',
                    'permission'        => array('system' => 'ipaddr'),
                    'description'       => $this->language->translate('HL_OPTIONS_IPBLOCKING'),
                    'class'             => '',
                    'id'                => 'nav-item-ips',
                    'icon'              => 'fa fa-unlock fa-fw'
                ]),
                navigationItem::createItemFromArray([
                    'url'               => 'wordban/list',
                    'permission'        => array('system' => 'wordban'),
                    'description'       => $this->language->translate('HL_OPTIONS_WORDBAN'),
                    'class'             => '',
                    'id'                => 'nav-item-wordban',
                    'icon'              => 'fa fa-ban fa-fw'
                ]),
                navigationItem::createItemFromArray([
                    'url'               => 'categories/list',
                    'permission'        => array('system' => 'categories'),
                    'description'       => $this->language->translate('HL_CATEGORIES_MNG'),
                    'class'             => '',
                    'id'                => 'nav-item-categories',
                    'icon'              => 'fa fa-file-o fa-fw'
                ]),
                navigationItem::createItemFromArray([
                    'url'               => 'system/templates',
                    'permission'        => array('system' => 'templates'),
                    'description'       => $this->language->translate('HL_OPTIONS_TEMPLATES'),
                    'class'             => '',
                    'id'                => '',
                    'icon'              => 'fa fa-code fa-fw'
                ]),
                navigationItem::createItemFromArray([
                    'url'               => 'smileys/list',
                    'permission'        => array('system' => 'smileys'),
                    'description'       => $this->language->translate('HL_OPTIONS_SMILEYS'),
                    'class'             => '',
                    'id'                => 'nav-item-smileys',
                    'icon'              => 'fa fa-smile-o fa-fw'
                ]),
                navigationItem::createItemFromArray([
                    'url'               => 'system/crons',
                    'permission'        => array('system' => 'crons'),
                    'description'       => $this->language->translate('HL_CRONJOBS'),
                    'class'             => '',
                    'id'                => '',
                    'icon'              => 'fa fa-history fa-fw'
                ]),
                navigationItem::createItemFromArray([
                    'url'               => 'system/logs',
                    'permission'        => array('system' => 'logs'),
                    'description'       => $this->language->translate('HL_LOGS'),
                    'class'             => '',
                    'id'                => '',
                    'icon'              => 'fa fa-exclamation-triangle fa-fw'
                ])
            );
            
            if (\fpcm\classes\baseconfig::$fpcmDatabase->getDbtype() == 'mysql') {
                $data[] = navigationItem::createItemFromArray([
                    'url'               => 'system/backups',
                    'permission'        => array('system' => 'backups'),
                    'description'       => $this->language->translate('HL_BACKUPS'),
                    'class'             => '',
                    'id'                => '',
                    'icon'              => 'fa fa-life-ring fa-fw'
                ]);
            }
            
            return $data;
        }

        /**
         * Erzeugt Submenü in Module
         * @return array
         */
        private function modulesSubmenu() {

            $items = array(
                navigationItem::createItemFromArray([
                    'url'               => 'modules/list',
                    'permission'        => array('modules' => array('install', 'uninstall', 'configure', 'enable')),
                    'description'       => $this->language->translate('HL_MODULES_MNG'),                    
                    'class'             => '',
                    'id'                => '',
                    'icon'              => 'fa fa-plug fa-fw'
                ])
            );

            $eventResult = $this->events->runEvent('navigationSubmenuModulesAdd', $items);
            if (count($eventResult) == count($items)) {            
                return $items;
            }

            $eventResult[0]->setSpacer(true);

            return $eventResult;
        }
    }
