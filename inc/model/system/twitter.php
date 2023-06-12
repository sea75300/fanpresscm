<?php

/**
 * Twitter object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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
     * @var \Abraham\TwitterOAuth
     */
    protected $oAuth;

    /**
     * bei Twitter angezeigter Benutzername
     * @var string
     */
    protected $username = '';

    /**
     * Requirements status
     * @var ?bool
     * @since 5.0.2
     */
    protected $requ = null;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();

        include_once \fpcm\classes\loader::libGetFilePath('twitteroauth');
        include_once \fpcm\classes\loader::libGetFilePath('ca-bundle/src/CaBundle.php');

        if (!$this->checkRequirements()) {
            return;
        }

        $this->oAuth = new \Abraham\TwitterOAuth\TwitterOAuth(
            $this->config->twitter_data->consumer_key,
            $this->config->twitter_data->consumer_secret,
            $this->config->twitter_data->user_token,
            $this->config->twitter_data->user_secret
        );
    }

    /**
     * Check, if connection requriements fit
     * @param bool $force force check
     * @return bool
     * @since 5.0.2
     */
    public function checkRequirements(bool $force = false) : bool
    {
        if (defined('FPCM_TWITTER_DSIABLE_API') && FPCM_TWITTER_DSIABLE_API) {
            return false;
        }
        
        if ($this->requ !== null && !$force) {
            return $this->requ;
        }

        $this->requ =   function_exists('curl_init') &&
                        \fpcm\classes\baseconfig::canConnect() &&
                        $this->config->twitter_data->isConfigured();

        return $this->requ;
    }

    /**
     * Prüft ob Verbindung zu Twitter besteht
     * @return bool
     */
    public function checkConnection()
    {
        if (defined('FPCM_TWITTER_DSIABLE_API') && FPCM_TWITTER_DSIABLE_API) {
            return false;
        }        
        
        $cacheName = 'twitter/checkConnection';

        if (!$this->cache->isExpired($cacheName)) {
            return $this->cache->read($cacheName);
        }

        if (!$this->checkRequirements()) {
            return false;
        }

        $result = $this->oAuth->get( 'account/verify_credentials', ['skip_status' => 1] );

        $this->log($result);

        $return = $this->oAuth->getLastHttpCode() != 200 ? false : true;
        $this->cache->write($cacheName, $return, $this->config->system_cache_timeout);

        return $return;
    }
    
    /**
     * Load twitter timeline
     * @return bool
     * @since 5.1.2-beta
     */
    public function canLoadTimeline() : bool
    {
        return $this->checkConnection() && (bool) $this->config->twitter_events->timeline;
    }

    /**
     * Sendet Request an Twitter, um Status zu aktualisieren
     * @param string $text
     * @return bool
     */
    public function updateStatus($text) : bool
    {
        if (defined('FPCM_TWITTER_DSIABLE_API') && FPCM_TWITTER_DSIABLE_API) {
            return false;
        }

        if (!trim($text)) {
            fpcmLogSystem('Create tweet failed, no text given!');
            return false;
        }

        $result = $this->oAuth->post('statuses/update', [ 'status' => $text ]);
        
        fpcmLogSystem(sprintf('Create new tweet: "%s"...', $text));
        $this->log($result);

        fpcmLogSystem(sprintf('Create tweet retuned code: %s', $this->oAuth->getLastHttpCode()));
        return $this->oAuth->getLastHttpCode() != 200 ? false : true;
    }

    /**
     * Fetch timeline data
     * @since 5.0.0-rc3
     */
    public function fetchTimeline() : string
    {
        if (!$this->canLoadTimeline()) {
            return '';
        }

        $result = $this->oAuth->get(
            'statuses/user_timeline',
            [
                'count' => $this->config->articles_acp_limit,
                'trim_user' => 1
            ]
        );
        
        if ($this->oAuth->getLastHttpCode() != 200) {
            $this->log($result);            
            fpcmLogSystem(sprintf('Failed to fetch twitter timeline, code: %s', $this->oAuth->getLastHttpCode()));
            return '';
        }

        $this->log($result);
        return json_encode($result);
    }

    /**
     * Loggt Twitter-response-Daten
     * @return bool
     */
    private function log($responseData)
    {
        if (defined('FPCM_TWITTER_DSIABLE_API') && FPCM_TWITTER_DSIABLE_API) {
            return false;
        }

        if (isset($responseData->errors)) {

            $i = 0;

            foreach ($responseData->errors as $value) {

                if ($value->code == 187) {
                    fpcmLogSystem("Twitter retuned Code {$value->code}: {$value->message}");
                    continue;
                }

                $i++;
                trigger_error("Twitter error code {$value->code} return. Message was: {$value->message}");
            }

            return $i ? false : true;
        }

        if (isset($responseData->screen_name)) {
            $this->username = $responseData->screen_name;
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
        if (defined('FPCM_TWITTER_DSIABLE_API') && FPCM_TWITTER_DSIABLE_API) {
            return '';
        }

        $cacheName = 'twitter/getUsername';

        if (!$this->cache->isExpired($cacheName) && !trim($this->username)) {
            $this->username = $this->cache->read($cacheName);
        }

        return $this->username;
    }

}
