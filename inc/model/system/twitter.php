<?php

/**
 * Twitter object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system;

/**
 * tmhOAuth wrapper Objekt für Twitter
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class twitter extends \fpcm\model\abstracts\staticModel {

    /**
     * tmhOAuth-Objekt
     * @var \tmhOAuth 
     */
    protected $oAuth;

    /**
     * bei Twitter angezeigter Benutzername
     * @var string
     */
    protected $username = '';

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();

        include_once \fpcm\classes\loader::libGetFilePath('tmhoauth/tmhOAuth.php');

        if (!$this->checkRequirements()) {
            return;
        }

        $this->oAuth = new \tmhOAuth((array) $this->config->twitter_data);
    }

    /**
     * Prüft, ob Verbindung zu Twitter hergestellt werden kann
     * @return bool
     */
    public function checkRequirements()
    {
        if (!\fpcm\classes\baseconfig::canConnect() || !function_exists('curl_init')) {
            return false;
        }

        if (!$this->config->twitter_data->isConfigured()) {
            return false;
        }

        return true;
    }

    /**
     * Prüft ob Verbindung zu Twitter besteht
     * @return bool
     */
    public function checkConnection()
    {
        $this->cacheName = 'twitter/checkConnection';

        if (!$this->cache->isExpired($this->cacheName)) {
            return $this->cache->read($this->cacheName);
        }

        if (!$this->checkRequirements()) {
            return false;
        }

        $code = $this->oAuth->request(
            'GET', $this->oAuth->url('1.1/account/verify_credentials')
        );

        $this->log();

        $return = ($code != 200 ? false : true);
        $this->cache->write($this->cacheName, $return, $this->config->system_cache_timeout);

        return $return;
    }

    /**
     * Sendet Request an Twitter, um Status zu aktualisieren
     * @param string $text
     * @return bool
     */
    public function updateStatus($text) : bool
    {
        if (!trim($text)) {
            fpcmLogSystem('Create tweet failed, no text given!');
            return false;
        }

        $code = $this->oAuth->request(
            'POST', $this->oAuth->url('1.1/statuses/update'), array('status' => $text)
        );
        
        $this->log();

        fpcmLogSystem('Create tweet retuned code: '.$code);
        return ($code != 200 ? false : true);
    }

    /**
     * Fetch timeline data
     * @since 5.0.0-rc3
     */
    public function fetchTimeline() : array
    {
        $cacheName = 'dashboard/twitter';

        $code = $this->oAuth->request(
            'GET',
            $this->oAuth->url('1.1/statuses/user_timeline'),
            [
                'count' => $this->config->articles_acp_limit,
                'trim_user' => 1
            ]
        );
        
        if ($code != 200) {
            trigger_error('Failed to fetch twitetr timeline');
            return [];
        }
        
        $this->log();
        return $this->oAuth->response['response'] ?? [];
    }

    /**
     * Loggt Twitter-response-Daten
     * @return bool
     */
    private function log()
    {
        $responseData = json_decode($this->oAuth->response['response'], true);

        if (isset($responseData['errors'])) {

            $i = 0;

            foreach ($responseData['errors'] as $value) {

                if ($value['code'] == 187) {
                    fpcmLogSystem("Twitter retuned Code {$value['code']}: {$value['message']}");
                    continue;
                }

                $i++;
                trigger_error("Twitter error code {$value['code']} return. Message was: {$value['message']}");
            }

            return $i ? false : true;
        }

        if (isset($responseData['screen_name'])) {
            $this->username = $responseData['screen_name'];
            $this->cache->write('twitter/getUsername', $this->username, $this->config->system_cache_timeout);
        }

        return true;
    }

    /**
     * Gibt Twitter-Benutzername zurück
     * @return string
     */
    public function getUsername()
    {
        $this->cacheName = 'twitter/getUsername';

        if (!$this->cache->isExpired($this->cacheName) && !trim($this->username)) {
            $this->username = $this->cache->read($this->cacheName);
        }

        return $this->username;
    }

}
