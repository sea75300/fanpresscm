<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

use fpcm\classes\baseconfig;

/**
 * Remote data model
 * 
 * @package fpcm\model\abstracts
 * @abstract
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class remoteModel extends staticModel {

    /**
     * Hinweis, dass allow_url_fopen nicht aktiv ist
     */
    const FURLOPEN_ERROR = 400;

    /**
     * Fehler beim Prüfung, ob Update-Server erreichbar ist
     */
    const REMOTEFILE_ERROR = 401;

    /**
     * Fehler beim Abrüfen der Update-Informationen
     */
    const REMOTECONTENT_ERROR = 402;

    /**
     * URL zum Server
     * @var string
     */
    protected $remoteServer = '';

    /**
     * Server port
     * @var string
     */
    protected $remotePort = 80;

    /**
     * Datenparameter
     * @var array
     */
    protected $checkParams = [];

    /**
     * Verbindungen zu anderem Server möglich
     * @var bool
     */
    protected $canConnect = false;

    /**
     * vom Server zurückgegebene Daten
     * @var string
     */
    protected $remoteData = '';

    /**
     * Konstruktor
     * @param int $init
     */
    public function __construct()
    {
        parent::__construct();
        $this->canConnect = baseconfig::canConnect();
    }

    /**
     * Ftech data from remote source
     * @return bool
     */
    public function fetchRemoteData()
    {
        if (!$this->canConnect) {
            return self::FURLOPEN_ERROR;
        }

        if (!$this->remoteAvailable()) {
            self::REMOTEFILE_ERROR;
        }

        $this->remoteData = file_get_contents($this->remoteServer);
        if (!$this->remoteData) {
            trigger_error('Error while fetching update informations from: ' . $this->remoteServer);
            return self::REMOTECONTENT_ERROR;
        }

        return true;
    }

    /**
     * Daten zurückgeben, die vom Server abgerufen wurden
     * @param string $key
     * @return array
     */
    public function getRemoteData($key = false)
    {
        return $key && isset($this->remoteData[$key]) ? $this->remoteData[$key] : $this->remoteData;
    }

    /**
     * Prüft, ob Update-Server verfügbar ist
     * @return bool
     */
    protected function remoteAvailable()
    {
        $remoteTest = @fsockopen(parse_url($this->remoteServer, PHP_URL_HOST), $this->remotePort);

        if (!$remoteTest) {
            trigger_error('Unable to connect to remote server: ' . $this->remoteUrl);
            return false;
        }

        fclose($remoteTest);

        return true;
    }

    /**
     * Writes remote repository data to local storage
     * @return bool
     */
    abstract protected function saveRemoteData();
}
