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
            navigationItem::AREA_DASHBOARD => array(
                (new navigationItem())->setUrl('system/dashboard')->setDescription('HL_DASHBOARD')->setIcon('home fa-lg')
            ),
            navigationItem::AREA_ADDNEWS => array(
                (new navigationItem())->setUrl('articles/add')
                    ->setDescription('HL_ARTICLE_ADD')
                    ->setIcon('pen-square fa-lg')
                    ->setPermission(['article' => 'add'])
            ),
            navigationItem::AREA_EDITNEWS => array(
                (new navigationItem())->setUrl('#')
                    ->setDescription('HL_ARTICLE_EDIT')
                    ->setIcon('book fa-lg')
                    ->setSubmenu($this->editorSubmenu())
                    ->setPermission(['article' => ['editall', 'edit', 'archive']])
                    ->setId('nav-id-editnews')
                    ->setClass('fpcm-navigation-noclick')
            ),
            navigationItem::AREA_COMMENTS => array(
                (new navigationItem())->setUrl('comments/list')
                    ->setDescription('HL_COMMENTS_MNG')
                    ->setIcon('comments fa-lg')
                    ->setId('nav-item-editcomments')
                    ->setPermission([
                        'article' => ['editall', 'edit'],
                        'comment' => ['editall', 'edit']
                    ])
            ),
            navigationItem::AREA_FILEMANAGER => array(
                (new navigationItem())->setUrl('files/list&mode=1')
                    ->setDescription('HL_FILES_MNG')
                    ->setIcon('folder-open fa-lg')
                    ->setPermission(['uploads' => 'visible'])
            ),
            navigationItem::AREA_OPTIONS => array(
                (new navigationItem())->setUrl('#')
                    ->setDescription('HL_OPTIONS')
                    ->setIcon('cog fa-lg')
                    ->setSubmenu($this->optionSubmenu())
                    ->setPermission(['system' => 'options'])
                    ->setId('fpcm-options-submenu')
                    ->setClass('fpcm-navigation-noclick')
            ),
            navigationItem::AREA_MODULES => array(
                (new navigationItem())->setUrl('modules/list')
                    ->setDescription('HL_MODULES')
                    ->setIcon('plug fa-lg')
                    ->setSubmenu($this->modulesSubmenu())
                    ->setPermission(['modules' => [
                        'install', 'uninstall', 'configure'
                    ]])
            ),
            navigationItem::AREA_TRASH => $this->addTrashItem(),
            navigationItem::AREA_AFTER => []
        ]);
    }

    /**
     * Add trash navigation items depending on delete permissions
     * @return array
     */
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
                ->setIcon('trash-alt', 'far')
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
            (new navigationItem())->setUrl('articles/listall')
                ->setDescription('HL_ARTICLE_EDIT_ALL')
                ->setIcon('book')
                ->setPermission(['article' => ['edit', 'editall']]),
            (new navigationItem())->setUrl('articles/listactive')
                ->setDescription('HL_ARTICLE_EDIT_ACTIVE')
                ->setIcon('newspaper', 'far')
                ->setPermission(['article' => 'edit']),
            (new navigationItem())->setUrl('articles/listarchive')
                ->setDescription('HL_ARTICLE_EDIT_ARCHIVE')
                ->setIcon('archive')
                ->setPermission(['article' => 'archive'])
        ];
    }

    /**
     * Erzeugt Optionen-Submenü
     * @return array
     */
    private function optionSubmenu()
    {
        $data = [
            (new navigationItem())->setUrl('system/options')
                ->setDescription('HL_OPTIONS_SYSTEM')
                ->setIcon('cog')
                ->setPermission(['system' => 'options']),
            (new navigationItem())->setUrl('users/list')
                ->setDescription('HL_OPTIONS_USERS')
                ->setIcon('users')
                ->setId('nav-item-users')
                ->setPermission(['system' => 'users', 'system' => 'rolls']),
            (new navigationItem())->setUrl('ips/list')
                ->setDescription('HL_OPTIONS_IPBLOCKING')
                ->setIcon('globe')
                ->setId('nav-item-ips')
                ->setPermission(['system' => 'ipaddr']),
            (new navigationItem())->setUrl('wordban/list')
                ->setDescription('HL_OPTIONS_WORDBAN')
                ->setIcon('ban')
                ->setId('nav-item-wordban')
                ->setPermission(['system' => 'wordban']),
            (new navigationItem())->setUrl('categories/list')
                ->setDescription('HL_CATEGORIES_MNG')
                ->setIcon('tags')
                ->setId('nav-item-categories')
                ->setPermission(['system' => 'categories']),
            (new navigationItem())->setUrl('templates/templates')
                ->setDescription('HL_OPTIONS_TEMPLATES')
                ->setIcon('code')
                ->setPermission(['system' => 'templates']),
            (new navigationItem())->setUrl('smileys/list')
                ->setDescription('HL_OPTIONS_SMILEYS')
                ->setIcon('smile-beam')
                ->setId('nav-item-smileys')
                ->setPermission(['system' => 'smileys']),
            (new navigationItem())->setUrl('system/crons')
                ->setDescription('HL_CRONJOBS')
                ->setIcon('history')
                ->setPermission(['system' => 'crons']),
            (new navigationItem())->setUrl('system/logs')
                ->setDescription('HL_LOGS')
                ->setIcon('exclamation-triangle')
                ->setPermission(['system' => 'logs']),
        ];

        if (\fpcm\classes\loader::getObject('\fpcm\classes\database')->getDbtype() == \fpcm\classes\database::DBTYPE_MYSQLMARIADB) {
            $data[] = (new navigationItem())->setUrl('system/backups')
                ->setDescription('HL_BACKUPS')
                ->setIcon('life-ring')
                ->setPermission(['system' => 'backups']);
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
        if (!is_array($items) || !count($items)) {
            return [];
        }

        return $items;
    }

}
