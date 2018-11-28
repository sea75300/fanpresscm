<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\users;

/**
 * Password check model
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
     * Konstruktor
     * @param string $pass
     */
    public function __construct(string $pass)
    {
        parent::__construct();
        $this->passHash = sha1($pass);
    }

    protected function saveRemoteData(): bool
    {
        return $this->cache->write('passec/'.$this->passHash, $this->remoteData);
    }

    public function isPowned()
    {
        $this->remoteData = $this->cache->read('passec/'.$this->passHash);
        if (!trim($this->remoteData) || $this->cache->isExpired('passec/'.$this->passHash)) {
            $this->remoteServer = 'https://api.pwnedpasswords.com/range/' . strtoupper(substr($this->passHash, 0, $this->passLimit));
            $this->fetchRemoteData();
            $this->cache->write('passec/'.$this->passHash, $this->remoteData);
        }

        $matches = [];
        $res = preg_match('/(' . substr($this->passHash, $this->passLimit) . ')(:)([0-9]+)/i', $this->remoteData, $matches);
        
        if (!count($matches) || empty($matches[1]) || empty($matches[2]) || empty($matches[3])) {
            return false;
        }

        if ((int) $matches[3] < 100) {
            return false;
        }
        
        return true;
        
    }

}
