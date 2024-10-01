<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\users;

/**
 * Benutzerrolle Objekt
 * 
 * @package fpcm\model\user
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class userRoll
extends
    \fpcm\model\abstracts\dataset
implements
    \fpcm\model\interfaces\isCopyable {

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
     * System roll flag
     * @var bool
     * @since 5.0.0-a4
     */
    protected $is_system = 0;

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
        return (string) $this->leveltitle;
    }

    /**
     * Return codex
     * @return string
     * @since 4.5-b8
     */
    public function getCodex(): string
    {
        return (string) $this->codex;
    }

    /**
     * System roll flag set
     * @return bool
     */
    public function isSystemRoll(): bool
    {
        return (bool) $this->is_system;
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
            'is_system' => $this->is_system
        ])->getData();

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
        if ($this->is_system) {
            trigger_error('A system roll cannot be deleted, ID was ' . $this->id);
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
        if ($this->is_system && isset($this->data['old_leveltitle']) && $this->data['old_leveltitle'] !== $this->leveltitle) {
            trigger_error('A system roll cannot be renamed, ID was ' . $this->id);
            return false;
        }

        $this->dbcon->transaction();

        $this->removeBannedTexts();

        $return = false;

        $params = $this->events->trigger('userroll\update', [
            'leveltitle' => $this->leveltitle,
            'codex' => $this->codex,
        ])->getData();
        
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

    /**
     * Creates copy of user roll
     * @return int
     * @since 5.2.2-dev
     */
    public function copy(): int
    {
        $cn = self::class;

        $lt = $this->language->translate($this->leveltitle);
        
        /* @var $copy userRoll */
        $copy = new $cn();
        $copy->setRollName($this->language->translate('GLOBAL_COPY_OF', [$lt], true));
        $copy->setCodex($this->codex);

        if (!$copy->save()) {
            return 0;
        }
        
        $id = $copy->getId();

        $permNew = new \fpcm\model\permissions\permissions($id);
        $permNew->setPermissionData( (new \fpcm\model\permissions\permissions($this->id))->getPermissionData() );

        if (!$permNew->update()) {
            trigger_error(sprintf('Unable to copy permissions for %s, use default set instead.', $copy->getRollNameTranslated()));
        }

        return $id;
    }

}
