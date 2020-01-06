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
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\theme
 */
class navigation extends \fpcm\model\abstracts\staticModel {

    /**
     * Navigation rendern
     * @return array
     */
    public function render()
    {
        $navigation = $this->events->trigger('navigation\render', $this->getNavigation());
        foreach ($navigation as &$moduleOptions) {
            $moduleOptions = $this->checkPermissions($moduleOptions);
        }

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
        foreach ($navigation as $key => $value) {

            if ($value->hasSubmenu()) {
                $value->setSubmenu($this->checkPermissions($value->getSubmenu()));
            }

            $accesible = $value->isAccessible();
            if ($accesible !== null && !$accesible) {
                unset($navigation[$key]);
                continue;
            }
            elseif ($value->hasPermission() && $this->permissions->check($value->getPermission())) {
                unset($navigation[$key]);
                continue;
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
                    ->setAccessible($this->permissions->article->add)
            ),
            navigationItem::AREA_EDITNEWS => array(
                (new navigationItem())->setUrl('#')
                    ->setDescription('HL_ARTICLE_EDIT')
                    ->setIcon('book fa-lg')
                    ->setSubmenu($this->editorSubmenu())
                    ->setAccessible($this->permissions->editArticles() || $this->permissions->article->archive)
                    ->setId('nav-id-editnews')
                    ->setClass('fpcm-navigation-noclick')
            ),
            navigationItem::AREA_COMMENTS => array(
                (new navigationItem())->setUrl('comments/list')
                    ->setDescription('HL_COMMENTS_MNG')
                    ->setIcon('comments fa-lg')
                    ->setId('nav-item-editcomments')
                    ->setAccessible($this->permissions->editComments())
            ),
            navigationItem::AREA_FILEMANAGER => array(
                (new navigationItem())->setUrl('files/list&mode=1')
                    ->setDescription('HL_FILES_MNG')
                    ->setIcon('folder-open fa-lg')
                    ->setAccessible($this->permissions->uploads->visible)
            ),
            navigationItem::AREA_OPTIONS => array(
                (new navigationItem())->setUrl('#')
                    ->setDescription('HL_OPTIONS')
                    ->setIcon('cog fa-lg')
                    ->setSubmenu($this->optionSubmenu())
                    ->setAccessible($this->permissions->system->options)
                    ->setId('fpcm-options-submenu')
                    ->setClass('fpcm-navigation-noclick')
            ),
            navigationItem::AREA_MODULES => array(
                (new navigationItem())->setUrl('modules/list')
                    ->setDescription('HL_MODULES')
                    ->setIcon('plug fa-lg')
                    ->setSubmenu($this->modulesSubmenu())
                    ->setAccessible($this->permissions->modules->configure || $this->permissions->modules->install || $this->permissions->modules->uninstall)
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
        
        if ($this->permissions->article->delete) {
            $submenu[] = (new navigationItem())->setUrl('articles/trash')->setDescription('HL_ARTICLES')->setIcon('book');
        }

        if ($this->permissions->comment->delete) {
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
                ->setAccessible($this->permissions->editArticles()),
            (new navigationItem())->setUrl('articles/listactive')
                ->setDescription('HL_ARTICLE_EDIT_ACTIVE')
                ->setIcon('newspaper', 'far')
                ->setAccessible($this->permissions->article->edit),
            (new navigationItem())->setUrl('articles/listarchive')
                ->setDescription('HL_ARTICLE_EDIT_ARCHIVE')
                ->setIcon('archive')
                ->setAccessible($this->permissions->article->archive)
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
                ->setAccessible($this->permissions->system->options),
            (new navigationItem())->setUrl('users/list')
                ->setDescription('HL_OPTIONS_USERS')
                ->setIcon('users')
                ->setId('nav-item-users')
                ->setAccessible($this->permissions->system->users || $this->permissions->system->rolls),
            (new navigationItem())->setUrl('ips/list')
                ->setDescription('HL_OPTIONS_IPBLOCKING')
                ->setIcon('globe')
                ->setId('nav-item-ips')
                ->setAccessible($this->permissions->system->ipaddr),
            (new navigationItem())->setUrl('wordban/list')
                ->setDescription('HL_OPTIONS_WORDBAN')
                ->setIcon('ban')
                ->setId('nav-item-wordban')
                ->setAccessible($this->permissions->system->wordban),
            (new navigationItem())->setUrl('categories/list')
                ->setDescription('HL_CATEGORIES_MNG')
                ->setIcon('tags')
                ->setId('nav-item-categories')
                ->setAccessible($this->permissions->system->categories),
            (new navigationItem())->setUrl('templates/templates')
                ->setDescription('HL_OPTIONS_TEMPLATES')
                ->setIcon('code')
                ->setAccessible($this->permissions->system->templates),
            (new navigationItem())->setUrl('smileys/list')
                ->setDescription('HL_OPTIONS_SMILEYS')
                ->setIcon('smile-beam')
                ->setId('nav-item-smileys')
                ->setAccessible($this->permissions->system->smileys),
            (new navigationItem())->setUrl('system/crons')
                ->setDescription('HL_CRONJOBS')
                ->setIcon('history')
                ->setAccessible($this->permissions->system->crons),
            (new navigationItem())->setUrl('system/backups')
                ->setDescription('HL_BACKUPS')
                ->setIcon('life-ring')
                ->setAccessible($this->permissions->system->backups),
            (new navigationItem())->setUrl('system/logs')
                ->setDescription('HL_LOGS')
                ->setIcon('exclamation-triangle')
                ->setAccessible($this->permissions->system->logs),
        ];

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
