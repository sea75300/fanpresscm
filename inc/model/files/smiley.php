<?php

/**
 * Smiley file object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Smiley file objekt
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class smiley extends \fpcm\model\abstracts\file implements \Serializable, \JsonSerializable {

    /**
     * ID von Datei-Eintrag in DB
     * @var int
     */
    protected $id;

    /**
     * Smiley-Code, der in Artikeln und Kommentaren geparst werden soll
     * @var string
     */
    protected $thiscode;

    /**
     * Bild-Breite
     * @var int
     */
    protected $width;

    /**
     * Bild-Höhe
     * @var int
     */
    protected $height;

    /**
     * String in der Form width="" height=""
     * @var string
     */
    protected $whstring;

    /**
     * Felder die in Datenbank gespeichert werden können
     * @var array
     */
    protected $dbParams = ['smileycode', 'filename'];

    /**
     * Konstruktor
     * @param string $filename
     * @param bool $initDB
     */
    public function __construct($filename = '', $initDB = true)
    {
        $this->table = \fpcm\classes\database::tableSmileys;

        $filename = trim($filename) ? \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_SMILEYS, $filename) : '';

        parent::__construct($filename);

        if ($this->exists()) {
            $this->init($initDB);
        }
    }

    /**
     * Datensatz-ID
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Smiley-Code auslesen
     * @return string
     */
    public function getSmileyCode()
    {
        return $this->smileycode;
    }

    /**
     * Smiley-Breite auslesen
     * @return inz
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Smiley-Höhe auslesen
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * String width="" height="" auslesen
     * @return string
     */
    public function getWhstring()
    {
        return $this->whstring;
    }

    /**
     * Smiley-URL ausgeben
     * @return string
     */
    public function getSmileyUrl()
    {
        return \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_SMILEYS, $this->filename);
    }

    /**
     * Datensatz ID setzen
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Smiley-Code setzen
     * @param string $thiscode
     */
    public function setSmileycode($thiscode)
    {
        $this->smileycode = $thiscode;
    }

    /**
     * nicht verwendet
     * @param string $newname
     * @param string $userid
     * @return boolean
     */
    public function rename($newname, $userid = false)
    {
        return false;
    }

    /**
     * Smiley löschen
     * @return boolean
     */
    public function delete()
    {
        if (!$this->exists(true))
            return false;

        return $this->dbcon->delete($this->table, "smileycode = ?", array($this->smileycode));
    }

    /**
     * Speichert einen neuen Smiley-Eintrag in der Datenbank
     * @return boolean
     */
    public function save()
    {
        if ($this->exists(true))
            return false;

        $saveValues = $this->getSaveValues();
        $saveValues = $this->events->runEvent('smileySave', $saveValues);

        $this->cache->cleanup();

        return $this->dbcon->insert($this->table, $saveValues);
    }

    /**
     * nicht verwendet
     * @return boolean
     */
    public function update()
    {
        return false;
    }

    /**
     * Prüft ob Smiley existiert
     * @param bool $dbOnly
     * @return bool
     */
    public function exists($dbOnly = false)
    {
        if (!$this->smileycode) {
            return false;
        }

        $count = $this->dbcon->count($this->table, '*', "smileycode = ?", array($this->smileycode));
        if ($dbOnly) {
            return $count > 0 ? true : false;
        }

        return (parent::exists() && $count > 0) ? true : false;
    }

    /**
     * Gibt Speicher-Values zurück
     * @return array
     */
    protected function getSaveValues()
    {
        $values = [];
        foreach ($this->dbParams as $key) {
            $values[$key] = ($this->$key) ? $this->$key : '';
        }

        return $values;
    }

    /**
     * initialisiert Bild-Objekt
     * @param bool $initDB
     * @return boolean
     */
    protected function init($initDB)
    {
        if ($initDB) {
            $dbData = $this->dbcon->fetch($this->dbcon->select($this->table, 'id, smileycode, filename', "smileycode " . $this->dbcon->dbLike() . " ?", array($this->smileycode)));

            if (!$dbData)
                return false;

            foreach ($dbData as $key => $value) {
                $this->$key = $value;
            }
        }

        if (!parent::exists()) {
            return true;
        }
        
        $this->initImageSize();
    }

    /**
     * 
     * @return boolean
     */
    public function initImageSize()
    {
        $fileData = getimagesize($this->fullpath);

        if (is_array($fileData)) {
            $this->width = $fileData[0];
            $this->height = $fileData[1];
            $this->whstring = $fileData[3];
            $this->mimetype = $fileData['mime'];
        }

        return true;
    }

    /**
     * Serialisiert Smiley-Objekt für Cache-Speicherung
     * @return string
     */
    public function serialize()
    {
        $internal = get_object_vars($this);
        foreach ($internal as $key => $value) {
            if (is_object($value)) {
                unset($internal[$key]);
            }
        }

        return serialize($internal);
    }

    /**
     * Unserialisiert Smiley-Objekt aus Cache-Speicherung
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * 
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'img' => $this->getEditorImageTag(),
        ];
    }
    
    /**
     * Gibt Link für Edit-Action zurück
     * @return string
     */
    public function getEditLink()
    {
        return \fpcm\classes\tools::getFullControllerLink('smileys/edit', [
            'data' => urlencode(base64_encode(json_encode([
                'filename' => $this->filename,
                'code' => $this->smileycode,
                'id' => $this->id
            ])))
        ]);
    }

    /**
     * Returns smiley image tag
     * @return string
     * @since FPCM 4
     */
    public function getImageTag()
    {
        return "<img src=\"{$this->getSmileyUrl()}\" alt=\"{$this->getFilename()}\" {$this->getWhstring()}>";
    }

    /**
     * Returns smiley image tag
     * @return string
     * @since FPCM 4
     */
    public function getEditorImageTag()
    {
        return "<span class=\"fpcm-ui-padding-md-lr fpcm-ui-padding-md-tb\"><img role=\"option\" data-smileycode=\"{$this->getSmileyCode()}\" class=\"fpcm-editor-htmlsmiley\" src=\"{$this->getSmileyUrl()}\" title=\"{$this->getFilename()} ({$this->getSmileyCode()})\" {$this->getWhstring()}></span>";
    }
    
}

?>