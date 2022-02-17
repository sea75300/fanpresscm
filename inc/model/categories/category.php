<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\categories;

/**
 * Kategorie-Objekt
 * 
 * @package fpcm\model\categories
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class category extends \fpcm\model\abstracts\dataset
implements \fpcm\model\interfaces\isCsvImportable {

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
    protected $editAction = 'categories/edit&id=';

    /**
     * Wortsperren-Liste
     * @var \fpcm\model\wordban\items
     * @since 3.2.0
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
     * Liefert <img>-Tag für Kategorie-Icon zurück
     * @return string
     * @since 3.1.0
     */
    public function getCategoryImage()
    {
        if (!$this->getIconPath()) {
            return (new \fpcm\view\helper\icon('image'))->setStack('ban text-danger')->setStackTop(true)->setText('GLOBAL_NOTFOUND');
        }
        
        return '<img src="' . $this->getIconPath() . '" alt="' . $this->getName() . '" title="' . $this->getName() . '" class="fpcm-pub-category-icon">';
    }

    /**
     * Executes save process to database and events
     * @return bool|int
     */
    public function save()
    {
        $success = parent::save();
        if ($success === false && $this->dbcon->getLastQueryErrorCode() === \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY) {
            return \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY;
        }

        return $success;
    }

    /**
     * Executes update process to database and events
     * @return bool|int
     */
    public function update()
    {
        $success = parent::update();
        if ($success === false && $this->dbcon->getLastQueryErrorCode() === \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY) {
            return \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY;
        }

        return $success;
    }

    /**
     * Assigns csv row to internal fields
     * @param array $csvRow
     * @return bool
     * @since 4.5-b8
     */
    public function assignCsvRow(array $csvRow): bool 
    {
        $data = array_intersect_key($csvRow, array_flip($this->getFields()));

        if (!count($data)) {
            trigger_error('Failed to assign data, empty field set!');
            return false;
        }

        if (empty($data['name'])) {
            trigger_error('Failed to assign data, name cannot be empty!');
            return false;
        }

        if (empty($data['groups'])) {
            trigger_error('Failed to assign data, groups cannot be empty!');
            return false;
        }

        $obj = clone $this;

        $obj->setName($data['name']);        
        $obj->setGroups( implode(';', array_map( 'intval', explode(';', $data['groups']) ) ) );
        $obj->setIconPath($data['iconpath'] ?? '');

        if (!$obj->save())  {
            trigger_error('Failed to import category.'.PHP_EOL.PHP_EOL.print_r($data, true));
            return false;
        }

        unset($obj);
        return true;
    }

    /**
     * Fetch fields for mapping
     * @return array
     * @since 4.5-b8
     */
    public function getFields(): array
    {
        return [
            'CATEGORIES_NAME' => 'name',
            'CATEGORIES_ICON_PATH' => 'iconpath',
            'CATEGORIES_ROLLS' => 'groups',
        ];
    }

    /**
     * Führt Ersetzung von gesperrten Texten in Kommentar-Daten durch
     * @return bool
     * @since 3.2.0
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
     * @since 4.1
     */
    protected function getEventModule(): string
    {
        return 'category';
    }

    /**
     * Is triggered after successful database insert
     * @see \fpcm\model\abstracts\dataset::afterSaveInternal
     * @return bool
     * @since 4.1
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
     * @since 4.1
     */
    protected function afterUpdateInternal(): bool
    {
        $this->cache->cleanup();
        $this->init();
        return true;
    }

}
