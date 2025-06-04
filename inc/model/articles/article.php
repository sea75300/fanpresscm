<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\articles;

/**
 * Artikel object
 *
 * @package fpcm\model\articles
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class article
extends
    \fpcm\model\abstracts\dataset
implements
    \fpcm\model\interfaces\isCsvImportable,
    \fpcm\model\interfaces\isCopyable {

    use \fpcm\model\traits\autoTable,
        \fpcm\model\traits\statusIcons,
        \fpcm\model\traits\articles\csvUtils,
        \fpcm\model\traits\articles\iconUtils,
        \fpcm\model\traits\articles\revisionUtils,
        \fpcm\model\traits\articles\sourcesUtils;

    /**
     * Cache-Name für einzelnen Artikel
     * @since 3.4
     */
    const CACHE_ARTICLE_SINGLE = 'articlesingle';

    /**
     * Cache-Module-Name
     * @since 3.4
     */
    const CACHE_ARTICLE_MODULE = 'articles';

    /**
     * Sources auto-complete fileoption name
     * @since 4.1
     */
    const SOURCES_AUTOCOMPLETE = 'articles/sources';

    /**
     * Postpoed active status
     * @since 4.5
     */
    const POSTPONED_ACTIVE = 1;

    /**
     * Postpoed active status
     * @since 4.5
     */
    const POSTPONED_INACTIVE = 0;

    /**
     * Postpoed search status flag
     * @since 4.5
     */
    const POSTPONED_SEARCH_FE = 2;

    /**
     * News-Titel
     * @var string
     */
    protected $title = '';

    /**
     * News-Text
     * @var string
     */
    protected $content = '';

    /**
     * Kategorien
     * @var array
     */
    protected $categories = [];

    /**
     * Status: Entwurf
     * @var int
     */
    protected $draft = 0;

    /**
     * Status: archiviert
     * @var int
     */
    protected $archived = 0;

    /**
     * Status: gepinnt
     * @var int
     */
    protected $pinned = 0;

    /**
     * Status: automatisch freischalten
     * @var int
     */
    protected $postponed = 0;

    /**
     * Status: gelöscht
     * @var int
     */
    protected $deleted = 0;

    /**
     * Kommentare aktiv
     * @var int
     */
    protected $comments = 1;

    /**
     * Artikel muss freigegeben werden
     * @var int
     */
    protected $approval = 0;

    /**
     * Pfad zum Artikel-Bild
     * @var string
     * @since 3.1.0
     */
    protected $imagepath = '';

    /**
     * Veröffentlichungszeit
     * @var int
     */
    protected $createtime = 0;

    /**
     * Author
     * @var int
     */
    protected $createuser = 0;

    /**
     * Zeitpunkt der letzten Änderung
     * @var int
     */
    protected $changetime = 0;

    /**
     * Benutzer der letzten Änderung
     * @var int
     */
    protected $changeuser = 0;

    /**
     * Artikel-Quellen
     * @var string
     * @since 3.4
     */
    protected $sources = '';

    /**
     * Article relation id
     * @var int
     * @since 5.2.0-a1
     */
    protected $relates_to = 0;

    /**
     * Pin article until a certain date
     * @var int
     * @since 5.2.0-b4
     */
    protected $pinned_until = 0;

    /**
     * Artikel-Quellen
     * @var int
     * @since 3.5
     */
    protected $inedit = '';

    /**
     * Article url
     * @var string
     * @since 5.1-dev
     */
    protected $url = '';

    /**
     * richtiges Löschen erzwingen
     * @var int
     */
    protected $forceDelete = 0;

    /**
     * Auszuschließende Elemente beim in save/update
     * @var array
     */
    protected $dbExcludes = [
        'defaultPermissions',
        'forceDelete',
        'editPermission',
        'crypt'
    ];

    /**
     * Action-String für edit-Action
     * @var string
     */
    protected $editAction = 'articles/edit&id=';

    /**
     * Wortsperren-Liste
     * @var \fpcm\model\wordban\items
     * @since 3.2.0
     */
    protected $wordbanList;

    /**
     * Crypto-Objekt
     * @var \fpcm\classes\crypt
     * @since 3.6
     */
    protected $crypt;

    /**
     * Status ob Artikel bearbeitet werden kann
     * @var bool
     * @since 3.3
     */
    protected $editPermission = true;

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct($id = null)
    {
        $this->wordbanList = new \fpcm\model\wordban\items();
        $this->crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');

        parent::__construct($id);
    }

    /**
     * Gibt Artikel- zurück
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Gibt Artikel-Inhalt zurück
     * @return strig
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Gibt Artikel-Kategorie-IDs zurück
     * @return array
     */
    public function getCategories()
    {
        if (!is_array($this->categories)) {
            $this->categories = json_decode($this->categories, true);
        }

        return $this->categories;
    }

    /**
     * Gibt Artikel-Entwurf-Status zurück
     * @return bool
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * Gibt Artikel-archiviert-Status zurück
     * @return bool
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * Gibt Artikel-gepinnt-Status zurück
     * @return bool
     */
    public function getPinned()
    {
        return $this->pinned;
    }

    /**
     * Gibt Artikel-geplant-Status zurück
     * @return bool
     */
    public function getPostponed()
    {
        return $this->postponed;
    }

    /**
     * Gibt Artikel-gelöscht-Status zurück
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Gibt Artikel-Erstellungszeitpunkt zurück
     * @return int
     */
    public function getCreatetime()
    {
        return $this->createtime;
    }

    /**
     * Gibt Artikel-Author-ID zurück
     * @return int
     */
    public function getCreateuser()
    {
        return $this->createuser;
    }

    /**
     * Gibt Zeitpunkt der letzten Änderung zurück
     * @return int
     */
    public function getChangetime()
    {
        return $this->changetime;
    }

    /**
     * Gibt Benutzer-ID zurück, von dem letzte Änderung durchgeführt wurde
     * @return int
     */
    public function getChangeuser()
    {
        return $this->changeuser;
    }

    /**
     * Gibt Artikel-Kommentare aktiv-Status zurück
     * @return bool
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Gibt Artikel-muss freigeschaltet werden-Status zurück
     * @return bool
     */
    public function getApproval()
    {
        return $this->approval;
    }

    /**
     * Gibt Pfad zum Artikel-Bild zurück
     * @return string
     * @since 3.1.0
     */
    public function getImagepath()
    {
        return $this->imagepath;
    }

    /**
     * Liefert Status, ob Artikel bearbeitet werden kann zurück
     * @return bool
     * @since 3.3
     */
    public function getEditPermission()
    {
        return $this->editPermission;
    }

    /**
     * Gibt Artikel-Quellen zurück
     * @return bool
     * @since 3.4
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * Ttiel setzen
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = strip_tags($title, '<b><strong><i><em><u><span><br>');
    }

    /**
     * Inhalt setzen
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Kategorien setzen
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = json_encode($categories);
    }

    /**
     * Entwurf-Status setzen
     * @param bool $draft
     */
    public function setDraft($draft)
    {
        $this->draft = (int) $draft;
    }

    /**
     * archiviert Status setzen
     * @param bool $archived
     */
    public function setArchived($archived)
    {
        $this->archived = (int) $archived;
    }

    /**
     * gepinnt Status
     * @param bool $pinned
     */
    public function setPinned($pinned)
    {
        $this->pinned = (int) $pinned;
    }

    /**
     * Geplant-Status setzen
     * @param bool $postponed
     */
    public function setPostponed($postponed)
    {
        $this->postponed = (int) $postponed;
    }

    /**
     * Gelöscht Status setzen
     * @param bool $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = (int) $deleted;
    }

    /**
     * Zeitpunk der Erzeugung setzen
     * @param int $createtime
     */
    public function setCreatetime($createtime)
    {
        $this->createtime = (int) $createtime;
    }

    /**
     * Benutzer der Erzeugung setzen
     * @param int $createuser
     */
    public function setCreateuser($createuser)
    {
        $this->createuser = (int) $createuser;
    }

    /**
     * Zeitpunk der letzten Änderung setzen
     * @param int $changetime
     */
    public function setChangetime($changetime)
    {
        $this->changetime = (int) $changetime;
    }

    /**
     * Benutzer der letzten Änderung setzen
     * @param int $changeuser
     */
    public function setChangeuser($changeuser)
    {
        $this->changeuser = (int) $changeuser;
    }

    /**
     * Kommentar-aktiv-Status setzen
     * @param bool $comments
     */
    public function setComments($comments)
    {
        $this->comments = (int) $comments;
    }

    /**
     * Freigabe-Status setzen
     * @param bool $approval
     */
    public function setApproval($approval)
    {
        $this->approval = (int) $approval;
    }

    /**
     * Setzt Pfad zum Artikel-Bild
     * @param string $imagepath
     * @since 3.1.0
     */
    public function setImagepath($imagepath)
    {
        $this->imagepath = $imagepath;
    }

    /**
     * Setzt Artikel-Quellen-Daten
     * @param string $sources
     * @return bool
     * @since 3.4
     */
    public function setSources($sources)
    {
        return $this->sources = strip_tags($sources);
    }

    /**
     * Get article URL string
     * @return string
     * @since 5.1-dev
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get article URL string
     * @param string $url
     * @return void
     * @since 5.1-dev
     */
    public function setUrl(string $url): void
    {
        $this->url = $this->cleanupUrlString($url);
    }

    /**
     * Fetch article relation id
     * @return int
     * @since 5.2.0-a1
     */
    public function getRelatesTo(): int
    {
        return (int) $this->relates_to;
    }

    /**
     * Set article relation
     * @param int $relatesTo
     * @return void
     * @since 5.2.0-a1
     */
    public function setRelatesTo(int $relatesTo): void
    {
        $this->relates_to = $relatesTo;
    }

    /**
     * Get pinned until
     * @return int
     * @since 5.2.0-b4
     */
    public function getPinnedUntil(): int {
        return (int) $this->pinned_until;
    }

    /**
     * Set pinned until
     * @param int $pinnedUntil
     * @since 5.2.0-b4
     */
    public function setPinnedUntil(int $pinnedUntil) {
        $this->pinned_until = $pinnedUntil;
    }

    /**
     * Setzt Status, ob Artikel bearbeitet werden kann
     * @param bool $editPermission
     * @since 3.3
     */
    public function setEditPermission($editPermission)
    {
        $this->editPermission = $editPermission;
    }

    /**
     * Artikel vollständig löschen erzwingen
     * @param bool $forceDelete
     */
    public function setForceDelete($forceDelete)
    {
        $this->forceDelete = $forceDelete;
    }

    /**
     * schönen URL-Pfad zurückgeben
     * @return string
     */
    public function getArticleNicePath() : string
    {
        return rawurlencode($this->id . '-' . $this->getNicePathString() );
    }

    /**
     * Get nice article path string
     * @return string
     * @since 5.1-dev
     */
    public function getNicePathString() : string
    {
        return $this->cleanupUrlString( trim($this->url) ? $this->url : $this->title );
    }

    /**
     * Return frontend article link
     * @param string $params
     * @return string
     */
    public function getElementLink($params = '')
    {
        $idParam = ($this->config->articles_link_urlrewrite ? $this->getArticleNicePath() : $this->getId());

        if (!$this->config->system_mode) {
            return \fpcm\classes\tools::getFullControllerLink('fpcm/article', [
                'id' => $idParam
            ]);
        }

        return $this->config->system_url . '?module=fpcm/article&id=' . $idParam.$params;
    }

    /**
     * Link zum Löschen des Artikel-Caches
     * @return string
     * @since 3.6
     */
    public function getArticleCacheParams()
    {
        return ['cache' => urlencode($this->crypt->encrypt('article')), 'objid' => $this->id];
    }

    /**
     * Erzeugt Short-Link zum Artikel zurück
     * @return string
     */
    public function getArticleShortLink()
    {
        $elLink = $this->getElementLink();
        $elLinkEncode = urlencode($elLink);

        $external = !\fpcm\classes\baseconfig::canConnect() || (defined('FPCM_ARTICLE_DISABLE_SHORTLINKS') && FPCM_ARTICLE_DISABLE_SHORTLINKS) ? false : true;

        $return = $this->events->trigger('article\getShortLink', [
            'url' => $elLink,
            'encoded' => $elLinkEncode,
            'active' => $external,
            'default' => true,
        ])->getData();

        if (!$external) {
            return $elLink;
        }

        if (!$return['default']) {
            return $return['url'];
        }

        $shortened = file_get_contents('https://is.gd/create.php?format=simple&url=' . $elLinkEncode, false);
        if($shortened === false) {
            trigger_error('Unable to fetch short link data for '.$elLink.' , return with default article link.');
            return $elLink;
        }

        return $shortened;
    }

    /**
     * Liefert <img>-Tag für Artikel-Image zurück
     * @return string
     * @since 3.1.0
     */
    public function getArticleImage()
    {
        if (!trim($this->imagepath)) {
            return '';
        }

        return "<img loading=\"lazy\" class=\"fpcm-pub-article-image\" src=\"{$this->imagepath}\" alt=\"{$this->title}\" title=\"{$this->title}\" role=\"presentation\">";
    }

    /**
     * Löscht News in der Datenbank
     * @return bool
     */
    public function delete()
    {
        $this->cleanupCaches();

        if (!$this->forceDelete) {
            $this->deleted = 1;

            return $this->update();
        }

        $this->deleteRevisions();
        (new articleCategory($this->id, 0))->deleteByArticle();

        $commentList = new \fpcm\model\comments\commentList();
        $commentList->deleteCommentsByArticle($this->id);

        $return = parent::delete();
        $this->deleted = 1;

        return $return;
    }

    /**
     *
     * @return bool
     * @since 5.1.0-a1
     */
    public function pushCategories() : bool
    {
        $categories = $this->getCategories();

        if (!(new articleCategory($this->id, 0))->deleteByArticle()) {
            trigger_error(sprintf('Error while clean up article category assignement table for article %s', $this->id));
            return true;
        }

        array_walk($categories, fn($cid) => (new articleCategory($this->id, (int) $cid))->save() );
        return true;
    }

    /**
     * Sperrt Artikel als in Bearbeitung
     * @return bool
     * @since 3.5
     */
    public function setInEdit()
    {
        return $this->dbcon->update($this->table, ['inedit'], [time() . '-' . \fpcm\classes\loader::getObject('\fpcm\model\system\session')->getUserId(), $this->id], 'id = ?');
    }

    /**
     * In Bearbeitung Informationen auslesen
     * @return array
     * @since 3.5.3
     */
    public function getInEdit()
    {
        return explode('-', $this->inedit);
    }

    /**
     * Ist Artikel in Bearbeitung
     * @return bool
     * @since 3.5
     */
    public function isInEdit()
    {
        if (!trim($this->inedit)) {
            return false;
        }

        $data = explode('-', $this->inedit);
        return $data[0] > time() - FPCM_ARTICLE_LOCKED_INTERVAL && $data[1] != \fpcm\classes\loader::getObject('\fpcm\model\system\session')->getUserId() ? true : false;
    }

    /**
     * Prüft, ob Artikel öffentlich sichtbar ist
     * @return bool
     * @since 3.5
     */
    public function publicIsVisible()
    {
        $sessionExists = \fpcm\classes\loader::getObject('\fpcm\model\system\session')->exists();

        if (!$this->exists() || ($this->getDeleted() && !$sessionExists)) {
            return false;
        }

        if (($this->getDraft() || $this->getPostponed()) && !$sessionExists) {
            return false;
        }

        return true;
    }

    /**
     * Check if articles was created to display old article message
     * @return bool
     * @since 5.2.0-a1
     */
    public function isOldArticle() : bool
    {
        if ($this->archived) {
            return true;
        }

        return $this->createtime <= time() - FPCM_ARTICLES_OLDMESSAGE_INTERVALL;
    }

    /**
     * Creates copy of current article
     * @return int
     * @since 5.2.2-dev
     */
    public function copy(): int
    {
        $cn = self::class;

        /* @var $copy article */
        $copy = new $cn();
        $copy->setTitle($this->language->translate('GLOBAL_COPY_OF', [$this->title], true));
        $copy->setContent($this->content);
        $copy->setApproval($this->approval);
        $copy->setArchived($this->archived);
        $copy->setPinned($this->pinned);
        $copy->setPinnedUntil($this->pinned_until);
        $copy->setPostponed($this->postponed);
        $copy->setComments($this->comments);
        $copy->setCategories($this->getCategories());
        $copy->setImagepath($this->imagepath);
        $copy->setSources($this->sources);

        $copy->setRelatesTo($this->id);
        $copy->setCreateuser(\fpcm\model\system\session::getInstance()->getUserId());
        $copy->setCreatetime(time());

        return $copy->save() ?: 0;
    }

    /**
     * Führt Ersetzung von gesperrten Texten in Artikel-Daten durch
     * @return bool
     * @since 3.2.0
     */
    protected function removeBannedTexts()
    {
        if ($this->wordbanList->checkArticleApproval($this->title) ||
            $this->wordbanList->checkArticleApproval($this->content)) {
            $this->setApproval(1);
        }

        $this->title = $this->wordbanList->replaceItems($this->title);
        $this->content = $this->wordbanList->replaceItems($this->content);
        $this->imagepath = $this->wordbanList->replaceItems($this->imagepath);

        return true;
    }

    /**
     * Bereinigt Caches
     * @return void
     * @since 3.4-rc3
     */
    private function cleanupCaches()
    {
        $this->cache->cleanup(\fpcm\model\articles\article::CACHE_ARTICLE_MODULE . '/'.\fpcm\classes\cache::CLEAR_ALL);
        $this->cache->cleanup(\fpcm\model\articles\article::CACHE_ARTICLE_SINGLE . '/'.\fpcm\classes\cache::CLEAR_ALL);
        $this->cache->cleanup(\fpcm\model\pubtemplates\sharebuttons::CACHE_MODULE . '/'.\fpcm\classes\cache::CLEAR_ALL);
        $this->cache->cleanup(\fpcm\model\abstracts\dashcontainer::CACHE_M0DULE_DASHBOARD . '/'.\fpcm\classes\cache::CLEAR_ALL);
    }

    /**
     * Cleanup article url string
     * @param string $str
     * @return string
     */
    private function cleanupUrlString(string $str) : string
    {
        return rtrim(str_replace(['----', '--'], ['-', '-'], preg_replace( '/[\s\\\\\/\.\!\?\(\)\[\]]/i', '-', strtolower($str) ) ), '-');
    }

    /**
     * Returns event base string
     * @see \fpcm\model\abstracts\dataset::getEventModule
     * @return string
     * @since 4.1
     */
    protected function getEventModule() : string
    {
        return 'article';
    }

    /**
     * Is triggered after successful database insert
     * @see \fpcm\model\abstracts\dataset::afterSaveInternal
     * @return bool
     * @since 4.1
     */
    protected function afterSaveInternal(): bool
    {
        $this->pushCategories();
        $this->cleanupCaches();
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
        $this->pushCategories();
        $this->cleanupCaches();
        $this->init();
        return true;
    }

}