<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\users;

/**
 * Benutzerrolle Objekt
 * 
 * @package fpcm\model\user
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class userRoll extends \fpcm\model\abstracts\dataset {

    use \fpcm\model\traits\eventModuleEmpty;

    /**
     * Bezeichnung der Benutzer-Rolle
     * @var string
     */
    protected $leveltitle = '';

    /**
     * Roll codex
     * @var string
     * @since 4.5-b7
     */
    protected $codex = '';

    /**
     * Wortsperren-Liste
     * @var \fpcm\model\wordban\items
     * @since 3.2.0
     */
    protected $wordbanList;

    /**
     * Edit action string
     * @var string
     */
    protected $editAction = 'users/editroll&id=';

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct($id = null)
    {
        $this->table = \fpcm\classes\database::tableRoll;
        $this->wordbanList = new \fpcm\model\wordban\items();

        parent::__construct($id);
    }

    /**
     * Return (raw) roll name
     * @return string
     */
    public function getRollName(): string
    {
        return $this->leveltitle;
    }

    /**
     * Return codex
     * @return string
     * @since 4.5-b8
     */
    public function getCodex(): string
    {
        return $this->codex;
    }

    /**
     * Set roll name
     * @param string $leveltitle
     */
    public function setRollName(string $leveltitle)
    {
        $this->data['old_leveltitle'] = $this->leveltitle;
        $this->leveltitle = $leveltitle;
    }

    /**
     * Set codex
     * @param string $codex
     * @since 4.5-b8
     */
    public function setCodex(string $codex)
    {
        $this->codex = $codex;
    }
    
    /**
     * Returns translates roll name
     * @return string
     * @since 4.0.3
     */
    public function getRollNameTranslated() : string
    {
        return $this->language->translate($this->leveltitle);
    }

    /**
     * Speichert einen neuen Kommentar in der Datenbank
     * @return bool
     */
    public function save()
    {
        $this->dbcon->transaction();

        $this->removeBannedTexts();

        $params = $this->events->trigger('userroll\save', [
            'leveltitle' => $this->leveltitle,
            'codex' => $this->codex,
        ]);

        $newId = $this->dbcon->insert($this->table, $params);

        if (!$newId) {
            trigger_error('Failed to create new user roll "' . $this->leveltitle . '"');
            return $this->dbcon->getLastQueryErrorCode() === \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY
                ? \fpcm\drivers\sqlDriver::CODE_ERROR_UNIQUEKEY
                : false;
        }

        $permission = new \fpcm\model\permissions\permissions();
        $return = $permission->addDefault($newId);

        $this->dbcon->commit();

        $this->id = $newId;
        $this->cache->cleanup();

        return $return;
    }

    /**
     * Löscht eine Benutzer-Rolle in der Datenbank
     * @return bool
     */
    public function delete()
    {
        if ($this->id <= 3) {
            trigger_error('Tried to delete system roll with: ' . $this->id);
            return false;
        }

        $this->dbcon->transaction();

        $return = parent::delete();

        $permissions = new \fpcm\model\permissions\permissions($this->getId());

        if ($permissions->delete()) {
            $return = $return && true;
        }

        $this->dbcon->update(\fpcm\classes\database::tableAuthors, ['roll'], [-1, $this->id], 'id = ?');
        $this->dbcon->commit();
        $this->cache->cleanup();
        
        return $return;
    }

    /**
     * Aktualisiert eine Benutzer-Rolle in der Datenbank
     * @return bool
     */
    public function update()
    {
        if ($this->id <= 3 && isset($this->data['old_leveltitle']) && $this->data['old_leveltitle'] !== $this->leveltitle) {
            trigger_error('Tried to rename system roll with id ' . $this->id);
            return false;
        }

        $this->dbcon->transaction();

        $this->removeBannedTexts();

        $return = false;

        $params = $this->events->trigger('userroll\update', [
            'leveltitle' => $this->leveltitle,
            'codex' => $this->codex,
        ]);
        
        $params[] = $this->id;

        if ($this->dbcon->update($this->table, array_keys(array_slice($params, 0, 2)), array_values($params), 'id = ?')) {
            $return = true;
        }

        $this->dbcon->commit();
        $this->cache->cleanup();
        $this->init();

        return $return;
    }

    /**
     * Führt Ersetzung von gesperrten Texten in Kommentar-Daten durch
     * @return bool
     * @since 3.2.0
     */
    protected function removeBannedTexts()
    {
        $this->leveltitle = $this->wordbanList->replaceItems($this->leveltitle);
        $this->codex = $this->wordbanList->replaceItems($this->codex);
        return true;
    }

}
