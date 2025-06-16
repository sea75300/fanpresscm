<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\articles;

/**
 * Article revision object for storage in database
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\articles
 * @since 3.3
 */
class revision extends \fpcm\model\abstracts\dataset {

    /**
     * Article id
     * @var string
     */
    protected $article_id = 0;

    /**
     * Revision number
     * @var string
     */
    protected $revision_idx = 0;

    /**
     * Article data store din revision
     * @var array
     */
    protected $content = '';

    /**
     * Revision hash
     * @var string
     * @since 3.4
     */
    protected $hashsum = '';

    /**
     * Konstruktor
     * @param int $articleId
     * @param int $revisionIdx
     */
    public function __construct($articleId = 0, $revisionIdx = 0)
    {
        $this->article_id = (int) $articleId;
        $this->revision_idx = (int) $revisionIdx;

        $this->table = \fpcm\classes\database::tableRevisions;
        parent::__construct();

        if (!$this->article_id || !$this->revision_idx) {
            return true;
        }

        $this->init();
    }

    /**
     * Artikel ID zurückgeben
     * @return int
     */
    public function getArticleId()
    {
        return (int) $this->article_id;
    }

    /**
     * Revisionszeit-Index zurückgeben
     * @return int
     */
    public function getRevisionIdx()
    {
        return (int) $this->revision_idx;
    }

    /**
     * Artikel-COntent aus Revision zurückgeben
     * @return array
     */
    public function getContent()
    {

        $data = json_decode($this->content, true);
        if (!is_array($data)) {
            return [];
        }

        return $data;
    }

    /**
     * Hash von Revision zurückgeben
     * @return string
     * @since 3.4
     */
    public function getHashSum()
    {
        return $this->hashsum;
    }

    /**
     * Artikel ID setzen
     * @param int $articleId
     */
    public function setArticleId($articleId)
    {
        $this->article_id = (int) $articleId;
    }

    /**
     * Revisionszeit-Index setzen
     * @param int $revisionIdx
     */
    public function setRevisionIdx($revisionIdx)
    {
        $this->revision_idx = (int) $revisionIdx;
    }

    /**
     * Content aus artikel speichern
     * @param array $content
     */
    public function setContent(array $content)
    {
        $this->content = json_encode($content);
    }

    /**
     * Hash von Revision setzen
     * @param type $hashsum
     * @since 3.4
     */
    public function setHashsum($hashsum)
    {
        $this->hashsum = $hashsum;
    }

    /**
     * Speichert eine neue Revision in der Datenbank
     * @return int
     */
    public function save()
    {
        $params = $this->getPreparedSaveParams();
        if (!$this->dbcon->insert($this->table, $params)) {
            return false;
        }

        $this->id = $this->dbcon->getLastInsertId();
        return $this->id ? true : false;
    }

    /**
     * Revision kann nicht verändert werden
     * @return bool
     */
    public function update()
    {
        return false;
    }

    /**
     * Löscht Revision in der Datenbank
     * @return bool
     */
    public function delete()
    {
        $evbn = $this->getEventName('deleteBefore');
        $ev = $this->events->trigger($evbn, [
            'article_id' => $this->article_id,
            'revision_idx' => $this->revision_idx
        ]);

        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event %s failed. Returned success = %s, continue = %s", $evbn, $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $return = $this->dbcon->delete(
            $this->table,
            'article_id = ? AND revision_idx = ?',
            [$this->article_id, $this->revision_idx]
        );

        $evan = $this->getEventName('deleteAfter');
        $eva = $this->events->trigger($evan, [
            'article_id' => $this->article_id,
            'revision_idx' => $this->revision_idx
        ]);

        if (!$eva->getSuccessed() || !$eva->getContinue()) {
            trigger_error(sprintf("Event %s failed. Returned success = %s, continue = %s", $evan, $eva->getSuccessed(), $eva->getContinue()));
            return false;
        }

        return $return;
    }

    /**
     * Revision Hash Sum erzeugen
     * @param array $data
     * @since 3.4
     */
    public function createHashSum(array $data = [])
    {
        if (!count($data)) {
            $data = json_decode($this->content, true);
        }

        unset($data['changetime'], $data['changeuser']);
        return \fpcm\classes\tools::getHash(json_encode($data));
    }

    /**
     * Initializes object
     * @return bool
     */
    public function init()
    {
        $result = $this->dbcon->select(
            $this->table,
            'article_id, revision_idx, content',
            'article_id = ? AND revision_idx = ?',
            [$this->article_id, $this->revision_idx]
        );

        $object = $this->dbcon->fetch($result);
        if (!$object) {
            trigger_error('Failed to load data for object of type "' . static::class . '" with given id ' . $this->id . '!');
            return false;
        }

        $this->objExists = true;
        $this->createFromDbObject($object);

        return true;
    }

    /**
     * Returns event base string
     * @see \fpcm\model\abstracts\dataset::getEventModule
     * @return string
     * @since 4.1
     */
    protected function getEventModule() : string
    {
        return 'revision';
    }

}
