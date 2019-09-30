<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\categories;

/**
 * Kategorie-Objekt
 * 
 * @package fpcm\model\categories
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class category extends \fpcm\model\abstracts\dataset {

    /**
     * Kategorie-Name
     * @var string
     */
    protected $name;

    /**
     * Kategorie-Icon-Pfad
     * @var string
     */
    protected $iconpath;

    /**
     * Gruppen, die diese Kategorie nutzen dürfen
     * @var array
     */
    protected $groups;

    /**
     * Action-String für edit-Action
     * @var string
     */
    protected $editAction = 'categories/edit&categoryid=';

    /**
     * Wortsperren-Liste
     * @var \fpcm\model\wordban\items
     * @since FPCM 3.2.0
     */
    protected $wordbanList;

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct($id = null)
    {
        $this->table = \fpcm\classes\database::tableCategories;
        $this->wordbanList = new \fpcm\model\wordban\items();

        parent::__construct($id);
    }

    /**
     * Kategorie-Name
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Kategorie-Icon-Pfad
     * @var string
     */
    function getIconPath()
    {
        return $this->iconpath;
    }

    /**
     * Gruppen, die diese Kategorie nutzen dürfen
     * @var array
     */
    function getGroups()
    {
        return $this->groups;
    }

    /**
     * Kategorie-Name setzen
     * @param string $name
     */
    function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Kategorie-Icon-Pfad setzen
     * @param string $iconpath
     */
    function setIconPath($iconpath)
    {
        $this->iconpath = $iconpath;
    }

    /**
     * Gruppen, die diese Kategorie nutzen dürfen, setzen
     * @param array $groups
     */
    function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * existiert Kategorie?
     * @param string $name
     * @return bool
     * @since FPCM
     * @deprecated since version FPCM 4.1
     */
    private function categoryExists($name)
    {
        $counted = $this->dbcon->count("categories", 'id', "name ".$this->dbcon->dbLike()." ?", [$name]);
        return ($counted > 0) ? true : false;
    }

    /**
     * Liefert <img>-Tag für Kategorie-Icon zurück
     * @return string
     * @since FPCM 3.1.0
     */
    public function getCategoryImage()
    {
        return '<img src="' . $this->getIconPath() . '" alt="' . $this->getName() . '" title="' . $this->getName() . '" class="fpcm-pub-category-icon">';
    }

    /**
     * Führt Ersetzung von gesperrten Texten in Kommentar-Daten durch
     * @return bool
     * @since FPCM 3.2.0
     */
    protected function removeBannedTexts()
    {
        $this->name = $this->wordbanList->replaceItems($this->name);
        $this->iconpath = $this->wordbanList->replaceItems($this->iconpath);

        return true;
    }

    /**
     * Returns event base string
     * @see \fpcm\model\abstracts\dataset::getEventModule
     * @return string
     * @since FPCM 4.1
     */
    protected function getEventModule(): string
    {
        return 'category';
    }

    /**
     * Is triggered after successful database insert
     * @see \fpcm\model\abstracts\dataset::afterSaveInternal
     * @return bool
     * @since FPCM 4.1
     */
    protected function afterSaveInternal(): bool
    {
        $this->cache->cleanup();
        return true;
    }

    /**
     * Is triggered after successful database update
     * @see \fpcm\model\abstracts\dataset::afterUpdateInternal
     * @return bool
     * @since FPCM 4.1
     */
    protected function afterUpdateInternal(): bool
    {
        $this->cache->cleanup();
        $this->init();
        return true;
    }

}
