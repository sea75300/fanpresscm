<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\theme;

/**
 * ACP navigation Objekt
 * 
 * @author Stefan Seehafer aka imagine <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\theme
 */
class navigation extends \fpcm\model\abstracts\staticModel {

    /**
     * Navigation list
     * @var navigationList
     * @since 4.5
     */
    private $navList;

    /**
     * Constructor
     * @param string $activeNavItem
     */
    public function __construct(string $activeNavItem = '')
    {
        parent::__construct();
        $this->navList = new navigationList($activeNavItem ?? \fpcm\classes\tools::getNavigationActiveCheckStr());
    }
    
    /**
     * Navigation rendern
     * @return array
     */
    public function render()
    {
        $this->getNavigation();
        return $this->events->trigger('navigation\render', $this->navList)->getData();
    }

    /**
     * Baut Navigation auf
     * @return array
     */
    private function getNavigation()
    {
        $this->navList->add(
            navigationItem::AREA_DASHBOARD,
            (new navigationItem())->setUrl('system/dashboard')->setDescription('HL_DASHBOARD')->setIcon('home fa-lg')
        );

        $this->navList->add(
            navigationItem::AREA_ADDNEWS,
            (new navigationItem())->setUrl('articles/add')->setDescription('HL_ARTICLE_ADD')->setIcon('pen-square fa-lg')->setAccessible($this->permissions->article->add)
        );

        $this->navList->add(
            navigationItem::AREA_EDITNEWS,
            (new navigationItem())->setUrl('#')->setDescription('HL_ARTICLE_EDIT')
            ->setIcon('book fa-lg')->setSubmenu($this->editorSubmenu())
            ->setAccessible($this->permissions->editArticles() || $this->permissions->article->archive)
            ->setId('editnews')
        );

        $this->navList->add(
            navigationItem::AREA_COMMENTS,
            (new navigationItem())->setUrl('comments/list')->setDescription('HL_COMMENTS_MNG')
            ->setIcon('comments fa-lg')->setId('editcomments')
            ->setAccessible($this->config->system_comments_enabled && $this->permissions->editComments())
        );

        $this->navList->add(
            navigationItem::AREA_FILEMANAGER,
            (new navigationItem())->setUrl('files/list&mode=1')->setDescription('HL_FILES_MNG')
            ->setIcon('folder-open fa-lg')->setAccessible($this->permissions->uploads->visible)    
        );

        $this->navList->add(
            navigationItem::AREA_OPTIONS,
            (new navigationItem())->setUrl('#')->setDescription('HL_OPTIONS')
            ->setIcon('cog fa-lg')->setSubmenu($this->optionSubmenu())
            ->setAccessible($this->permissions->system->options)
            ->setId('options-submenu')
        );

        $this->navList->add(
            navigationItem::AREA_MODULES,
            (new navigationItem())->setUrl('modules/list')->setDescription('HL_MODULES')
            ->setIcon('plug fa-lg')->setSubmenu($this->modulesSubmenu())
            ->setAccessible($this->permissions->modules->configure || $this->permissions->modules->install || $this->permissions->modules->uninstall)
        );

        $this->addTrashItem();
        $this->addUtilitiesItem();

        return $this->events->trigger('navigation\add', $this->navList)->getData();
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

        if ($this->config->system_comments_enabled && $this->permissions->comment->delete) {
            $submenu[] = (new navigationItem())->setUrl('comments/trash')->setDescription('COMMMENT_HEADLINE')->setIcon('comments');
        }

        if (!count($submenu)) {
            return false;
        }
                
        $this->navList->add(
            navigationItem::AREA_TRASH,
            (new navigationItem())->setUrl('#')
            ->setDescription('ARTICLES_TRASH')
            ->setIcon('trash-alt', 'far')
            ->setId('trashmain')
            ->setSubmenu($submenu)
        );

        return true;
    }

    /**
     * Add trash navigation items depending on delete permissions
     * @return array
     */
    private function addUtilitiesItem()
    {
        if (!$this->permissions->system->options) {
            return true;
        }
        
        $submenu = [];

        if ($this->permissions->system->csvimport) {
            $submenu[] = (new navigationItem())->setUrl('system/import')->setDescription('IMPORT_MAIN')->setIcon('file-import');
        }

        if (FPCM_DEBUG && defined('FPCM_LANG_XML')) {
            $submenu[] = (new navigationItem())->setUrl('system/langedit')->setDescription('Language Editor')->setIcon('language');
        }

        if (!count($submenu)) {
            return false;
        }
                
        $this->navList->add(
            navigationItem::AREA_TRASH,
            (new navigationItem())->setUrl('#')->setDescription('Werkzeuge')
            ->setIcon('tools')
            ->setId('utilities')
            ->setSubmenu($submenu)
        );

        return true;
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
                ->setId('users')
                ->setAccessible($this->permissions->system->users || $this->permissions->system->rolls),
            (new navigationItem())->setUrl('ips/list')
                ->setDescription('HL_OPTIONS_IPBLOCKING')
                ->setIcon('globe')
                ->setId('ips')
                ->setAccessible($this->permissions->system->ipaddr),
            (new navigationItem())->setUrl('wordban/list')
                ->setDescription('HL_OPTIONS_WORDBAN')
                ->setIcon('ban')
                ->setId('wordban')
                ->setAccessible($this->permissions->system->wordban),
            (new navigationItem())->setUrl('categories/list')
                ->setDescription('HL_CATEGORIES_MNG')
                ->setIcon('tags')
                ->setId('categories')
                ->setAccessible($this->permissions->system->categories),
            (new navigationItem())->setUrl('templates/templates')
                ->setDescription('HL_OPTIONS_TEMPLATES')
                ->setIcon('code')
                ->setAccessible($this->permissions->system->templates),
            (new navigationItem())->setUrl('smileys/list')
                ->setDescription('HL_OPTIONS_SMILEYS')
                ->setIcon('smile-beam')
                ->setId('smileys')
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
                ->setAccessible($this->permissions->system->logs)
        ];

        return $data;
    }

    /**
     * Erzeugt Submenü in Module
     * @return array
     */
    private function modulesSubmenu()
    {
        $items = $this->events->trigger('navigation\addSubmenuModules')->getData();
        if (!is_array($items) || !count($items)) {
            return [];
        }

        return $items;
    }

}
