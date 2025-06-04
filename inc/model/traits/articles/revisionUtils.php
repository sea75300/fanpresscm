<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits\articles;

/**
 * Article revision utils
 *
 * @package fpcm\model\traits\articles
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.0-a1
 */
trait revisionUtils {

    /**
     * Artikel-Daten für Revision vorbereiten
     * @since 3.4
     */
    public function prepareRevision()
    {
        $this->data['preparedRevision'] = $this->getPreparedSaveParams();
    }

    /**
     * Erzeugt eine Revision des Artikels
     * @param int $timer
     * @return bool
     */
    public function createRevision($timer = 0)
    {
        $ev = $this->events->trigger('revision\create', $this->getPreparedSaveParams());
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event revision\create failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $content = $ev->getData();

        if (!$timer) {
            $timer = $this->changetime;
        }

        $revision = new \fpcm\model\articles\revision();
        $revision->setArticleId($this->id);
        $revision->setRevisionIdx($timer);
        $revision->setContent($content);

        $newHash = $revision->createHashSum();
        $revision->setHashsum($newHash);

        if (isset($this->data['preparedRevision']) &&
                is_array($this->data['preparedRevision']) &&
                $revision->createHashSum($this->data['preparedRevision']) === $newHash) {
            return true;
        }

        if (!$revision->save()) {
            trigger_error('Unable to create revision for article ' . $this->id);
            return false;
        }

        return true;
    }

    /**
     * Gib Revisionen des Artikels zurück
     * @param bool $full Soll die Revision ganz zurückgegebn werden oder nur Titel
     * @return array
     */
    public function getRevisions($full = false)
    {
        $result = $this->dbcon->select(
            \fpcm\classes\database::tableRevisions,
            'article_id, revision_idx, content',
            'article_id = ? ' . $this->dbcon->orderBy(array('revision_idx DESC')),
            array($this->id)
        );

        $revisionSets = $this->dbcon->fetch($result, true);
        if (!is_array($revisionSets) || !count($revisionSets)) {
            return [];
        }

        $ev = $this->events->trigger('revision\getBefore', $revisionSets);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event revision\getBefore failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return [];
        }

        $revisionSets = $ev->getData();

        $revisions = [];
        foreach ($revisionSets as $revisionSet) {

            $revisionObj = new \fpcm\model\articles\revision($this->id);
            $revisionObj->createFromDbObject($revisionSet);

            $revData = $revisionObj->getContent();
            $revTime = $revisionObj->getRevisionIdx();

            if (!is_array($revData) || !$revTime) {
                continue;
            }

            $revisions[$revTime] = $full ? $revData : $revData['title'];
        }

        $ev = $this->events->trigger('revision\getAfter', [
            'full' => $full,
            'revisions' => $revisions
        ]);

        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event revision\getAfter failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return [];
        }

        $revisions = $ev->getData();
        if (!isset($revisions['revisions'])) {
            trigger_error('Event revision\getAfter did not returned revisions data');
            return [];
        }

        return $revisions;
    }

    /**
     * Anzahl Revisionen des Artikels
     * @return array
     * @since 3.6
     */
    public function getRevisionsCount()
    {
        return $this->dbcon->count(\fpcm\classes\database::tableRevisions, 'id', 'article_id = ? ', [$this->id]);
    }

    /**
     * Lädt Revision eines Artikels
     * @param int $revisionTime Revisions-ID
     * @return bool
     */
    public function getRevision($revisionTime)
    {
        $revision = new \fpcm\model\articles\revision($this->id, $revisionTime);
        if (!$revision->exists()) {
            return false;
        }

        $ev = $this->events->trigger('revision\get', $revision);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event revision\get failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return [];
        }

        $revision = $ev->getData();
        foreach ($revision->getContent() as $key => $value) {
            $this->$key = $value;
        }

        return true;
    }

    /**
     * Stellt Revision eines Artikels wieder her
     * @param int $revisionTime Revisions-ID
     * @return bool
     */
    public function restoreRevision($revisionTime)
    {
        if (!$this->createRevision(time())) {
            return false;
        }

        $this->getRevision($revisionTime);
        return $this->update();
    }

    /**
     * Löscht Revisionen
     * @param array $revisionList Liste von Revisions-IDs
     * @return bool
     */
    public function deleteRevisions(array $revisionList = [])
    {
        if (!count($revisionList)) {
            return $this->dbcon->delete(\fpcm\classes\database::tableRevisions, 'article_id = ?', array($this->id));
        }

        return $this->dbcon->delete(\fpcm\classes\database::tableRevisions, 'article_id = ? AND revision_idx IN (' . implode(',', array_map('intval', $revisionList)) . ')', array($this->id));
    }

}
