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

        $ev = $this->events->trigger($this->getEventName('save'), [
            'leveltitle' => $this->leveltitle,
            'codex' => $this->codex,
            'is_system' => $this->is_system
        ]);

        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event userroll\save failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $params = $ev->getData();
        if (!is_array($params)) {
            trigger_error(__METHOD__ . ' save params must be an array!');
            return false;
        }

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

        $evbn = $this->getEventName('deleteBefore');
        $evb = $this->events->trigger($evbn, $this->id);

        if (!$evb->getSuccessed() || !$evb->getContinue()) {
            trigger_error(sprintf("Event %s failed. Returned success = %s, continue = %s", $evbn, $evb->getSuccessed(), $evb->getContinue()));
            return false;
        }

        $this->dbcon->delete($this->table, 'id = ?', [$this->id]);

        $permissions = new \fpcm\model\permissions\permissions($this->getId());

        $return = false;
        if ($permissions->delete()) {
            $return = true;
        }

        $this->dbcon->update(\fpcm\classes\database::tableAuthors, ['roll'], [-1, $this->id], 'id = ?');
        $this->dbcon->commit();

        $evan = $this->getEventName('deleteAfter');
        $eva = $this->events->trigger($evan, $this->id);

        if (!$eva->getSuccessed() || !$eva->getContinue()) {
            trigger_error(sprintf("Event %s failed. Returned success = %s, continue = %s", $evan, $eva->getSuccessed(), $eva->getContinue()));
            return false;
        }

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

        $params = [
            'leveltitle' => $this->leveltitle,
            'codex' => $this->codex,
            'is_system' => $this->is_system,
            $this->id
        ];

        $ev = $this->events->trigger($this->getEventName('update'), $params);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event userroll\update failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $params = $ev->getData();
        if (!is_array($params)) {
            trigger_error(__METHOD__ . ' save params must be an array!');
            return false;
        }
        unset($params['is_system']);

        $fields = $this->getFieldFromSaveParams($params);

        if ($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
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

    /**
     * Returns event base string
     * @see \fpcm\model\abstracts\dataset::getEventModule
     * @return string
     * @since 4.1
     */
    protected function getEventModule(): string
    {
        return 'userroll';
    }
}
