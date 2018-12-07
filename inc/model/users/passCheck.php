<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\users;

/**
 * pwnedpasswords.com password check model 
 * @package fpcm\model\user
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.1
 */
class passCheck extends \fpcm\model\abstracts\remoteModel {

    /**
     * SHA1 password hash
     * @var string
     */
    protected $passHash = '';

    /**
     * Password delimiter
     * @var int
     */
    protected $passLimit = 5;

    /**
     * Server port
     * @var string
     */
    protected $remotePort = 443;

    /**
     * Server string
     * @var string
     */
    protected $remoteServerBase = 'https://api.pwnedpasswords.com/range/';

    /**
     * Konstruktor
     * @param string $pass
     */
    public function __construct(string $pass)
    {
        parent::__construct();
        $this->passHash = sha1($pass);
        $this->cacheName = 'passec/' . $this->passHash;
    }

    /**
     * Writes remote repository data to local storage
     * @return bool
     */
    protected function saveRemoteData(): bool
    {
        return $this->cache->write($this->cacheName, $this->remoteData);
    }

    /**
     * Checks password string against pwnedpasswords.com, transfers an 5-char SHA1 hash
     * @return bool true if password is not in returned list or count is then 100
     */
    public function isPowned(): bool
    {
        if (!$this->canConnect) {
            return false;
        }

        if (!\fpcm\classes\baseconfig::installerEnabled()) {
            if (!$this->config->system_passcheck_enabled) {
                return false;
            }

            $this->remoteData = $this->cache->read($this->cacheName);
            if (!trim($this->remoteData) || $this->cache->isExpired($this->cacheName)) {
                $this->remoteServer = $this->remoteServerBase . strtoupper(substr($this->passHash, 0, $this->passLimit));
                $this->fetchRemoteData();
                $this->cache->write($this->cacheName, $this->remoteData);
            }
        }


        $matches = [];
        $res = preg_match('/(' . substr($this->passHash, $this->passLimit) . ')(:)([0-9]+)/i', $this->remoteData, $matches);

        if (!count($matches) || empty($matches[1]) || empty($matches[2]) || empty($matches[3])) {
            return false;
        }

        return (int) $matches[3] < 100 ? false : true;
    }

}
