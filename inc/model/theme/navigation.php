<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\theme;

/**
 * ACP navigation Objekt
 * 
 * @author Stefan Seehafer aka imagine <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\theme
 */
class navigation extends \fpcm\model\abstracts\staticModel {

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();

        $this->cacheName = 'theme/navigation_' . $this->session->getUserId();
    }

    /**
     * Navigation rendern
     * @return array
     */
    public function render()
    {
        if (!$this->cache->isExpired($this->cacheName)) {
            return $this->cache->read($this->cacheName);
        }

        $this->permissions = \fpcm\classes\loader::getObject('\fpcm\model\system\permissions');
        $navigation = $this->events->trigger('navigation\render', $this->getNavigation());

        foreach ($navigation as &$moduleOptions) {
            $moduleOptions = $this->checkPermissions($moduleOptions);
        }

        $this->cache->write($this->cacheName, $navigation, $this->config->system_cache_timeout);

        return $navigation;
    }

    /**
     * Berechtigungen für Zugriff auf Module prüfen
     * @param array $navigation
     * @return array
     */
    private function checkPermissions($navigation)
    {
        /* @var $value navigationItem */
        foreach ($navigation as $key => &$value) {

            if (is_array($value)) {
                trigger_error('Using an array as navigation item is deprecated as of FPCM 3.5. Create an instance of "\fpcm\model\theme\navigationItem" instead.' . PHP_EOL . print_r($value, true));
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
    private function getNavigation()
    {
        return $this->events->trigger('navigation\add', [
            'showMenu' => array(
                (new navigationItem())->setUrl('#')
                    ->setDescription('NAVIGATION_SHOW')
                    ->setIcon('bars')
                    ->setId('showMenu')
                    ->setClass('fpcm-navigation-noclick')
            ),
            'dashboard' => array(
                (new navigationItem())->setUrl('system/dashboard')->setDescription('HL_DASHBOARD')->setIcon('home')
            ),
            'addnews' => array(
                (new navigationItem())->setUrl('articles/add')
                    ->setDescription('HL_ARTICLE_ADD')
                    ->setIcon('pen-square')
                    ->setPermission(['article' => 'add'])
            ),
            'editnews' => array(
                (new navigationItem())->setUrl('#')
                    ->setDescription('HL_ARTICLE_EDIT')
                    ->setIcon('book')
                    ->setSubmenu(self::editorSubmenu())
                    ->setPermission(['article' => 'edit'])
                    ->setId('nav-id-editnews')
                    ->setClass('fpcm-navigation-noclick')
            ),
            'comments' => array(
                (new navigationItem())->setUrl('comments/list')
                    ->setDescription('COMMMENT_HEADLINE')
                    ->setIcon('comments')
                    ->setPermission([
                        'article' => ['editall', 'edit'],
                        'comment' => ['editall', 'edit']
                    ])
            ),
            'filemanager' => array(
                (new navigationItem())->setUrl('files/list&mode=1')
                    ->setDescription('HL_FILES_MNG')
                    ->setIcon('folder-open')
                    ->setPermission(['uploads' => 'visible'])
            ),
            'options' => array(
                (new navigationItem())->setUrl('#')
                    ->setDescription('HL_OPTIONS')
                    ->setIcon('cog')
                    ->setSubmenu($this->optionSubmenu())
                    ->setPermission(['system' => 'options'])
                    ->setId('fpcm-options-submenu')
                    ->setClass('fpcm-navigation-noclick')
            ),
            'modules' => array(
                (new navigationItem())->setUrl('modules/list')
                    ->setDescription('HL_MODULES')
                    ->setIcon('plug')
                    ->setSubmenu($this->modulesSubmenu())
                    ->setPermission(['modules' => [
                        'install', 'uninstall', 'configure'
                    ]])
            ),
            'trashes' => $this->addTrashItem(),
            'after' => []
        ]);
    }

    private function addTrashItem()
    {
        $submenu = [];
        
        if ($this->permissions->check(['article' => 'delete'])) {
            $submenu[] = (new navigationItem())->setUrl('articles/trash')->setDescription('HL_ARTICLES')->setIcon('book');
        }

        if ($this->permissions->check(['comment' => 'delete'])) {
            $submenu[] = (new navigationItem())->setUrl('comments/trash')->setDescription('COMMMENT_HEADLINE')->setIcon('comments');
        }

        if (!count($submenu)) {
            return $submenu;
        }

        return [
            (new navigationItem())->setUrl('#')
                ->setDescription('ARTICLES_TRASH')
                ->setIcon('trash-alt')
                ->setId('nav-id-trashmain')
                ->setClass('fpcm-navigation-noclick')
                ->setSubmenu($submenu)
        ];
    }

    /**
     * Erzeugt Submenü für News bearbeiten
     * @return array
     */
    private function editorSubmenu()
    {
        return [
            navigationItem::createItemFromArray([
                'url' => 'articles/listall',
                'permission' => array('article' => 'edit', 'article' => 'editall'),
                'description' => $this->language->translate('HL_ARTICLE_EDIT_ALL'),
                'icon' => 'fa fa-book fa-fw'
            ]),
            navigationItem::createItemFromArray([
                'url' => 'articles/listactive',
                'permission' => array('article' => 'edit'),
                'description' => $this->language->translate('HL_ARTICLE_EDIT_ACTIVE'),
                'icon' => 'far fa-newspaper fa-fw'
            ]),
            navigationItem::createItemFromArray([
                'url' => 'articles/listarchive',
                'permission' => array('article' => 'edit', 'article' => 'editall', 'article' => 'archive'),
                'description' => $this->language->translate('HL_ARTICLE_EDIT_ARCHIVE'),
                'icon' => 'fa fa-archive fa-fw'
            ])
        ];
    }

    /**
     * Erzeugt Optionen-Submenü
     * @return array
     */
    private function optionSubmenu()
    {
        $data = array(
            navigationItem::createItemFromArray([
                'url' => 'system/options',
                'permission' => array('system' => 'options'),
                'description' => $this->language->translate('HL_OPTIONS_SYSTEM'),
                'icon' => 'fa fa-cog fa-fw'
            ]),
            navigationItem::createItemFromArray([
                'url' => 'users/list',
                'permission' => array('system' => 'users', 'system' => 'rolls'),
                'description' => $this->language->translate('HL_OPTIONS_USERS'),
                'id' => 'nav-item-users',
                'icon' => 'fa fa-users fa-fw'
            ]),
            navigationItem::createItemFromArray([
                'url' => 'ips/list',
                'permission' => array('system' => 'ipaddr'),
                'description' => $this->language->translate('HL_OPTIONS_IPBLOCKING'),
                'id' => 'nav-item-ips',
                'icon' => 'fa fa-globe fa-fw'
            ]),
            navigationItem::createItemFromArray([
                'url' => 'wordban/list',
                'permission' => array('system' => 'wordban'),
                'description' => $this->language->translate('HL_OPTIONS_WORDBAN'),
                'id' => 'nav-item-wordban',
                'icon' => 'fa fa-ban fa-fw'
            ]),
            navigationItem::createItemFromArray([
                'url' => 'categories/list',
                'permission' => array('system' => 'categories'),
                'description' => $this->language->translate('HL_CATEGORIES_MNG'),
                'id' => 'nav-item-categories',
                'icon' => 'fa fa-tags fa-fw'
            ]),
            navigationItem::createItemFromArray([
                'url' => 'templates/templates',
                'permission' => array('system' => 'templates'),
                'description' => $this->language->translate('HL_OPTIONS_TEMPLATES'),
                'icon' => 'fa fa-code fa-fw'
            ]),
            navigationItem::createItemFromArray([
                'url' => 'smileys/list',
                'permission' => array('system' => 'smileys'),
                'description' => $this->language->translate('HL_OPTIONS_SMILEYS'),
                'id' => 'nav-item-smileys',
                'icon' => 'far fa-smile fa-fw'
            ]),
            navigationItem::createItemFromArray([
                'url' => 'system/crons',
                'permission' => array('system' => 'crons'),
                'description' => $this->language->translate('HL_CRONJOBS'),
                'icon' => 'fa fa-history fa-fw'
            ]),
            navigationItem::createItemFromArray([
                'url' => 'system/logs',
                'permission' => array('system' => 'logs'),
                'description' => $this->language->translate('HL_LOGS'),
                'icon' => 'fa fa-exclamation-triangle fa-fw'
            ])
        );

        if (\fpcm\classes\loader::getObject('\fpcm\classes\database')->getDbtype() == \fpcm\classes\database::DBTYPE_MYSQLMARIADB) {
            $data[] = navigationItem::createItemFromArray([
                'url' => 'system/backups',
                'permission' => array('system' => 'backups'),
                'description' => $this->language->translate('HL_BACKUPS'),
                'icon' => 'fa fa-life-ring fa-fw'
            ]);
        }

        return $data;
    }

    /**
     * Erzeugt Submenü in Module
     * @return array
     */
    private function modulesSubmenu()
    {
        $items = $this->events->trigger('navigation\addSubmenuModules');
        if (!count($items)) {
            return [];
        }

        return $items;
    }

}
