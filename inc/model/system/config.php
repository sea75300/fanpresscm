<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system;

use fpcm\classes\baseconfig;
use fpcm\classes\database;
use fpcm\classes\loader;
use fpcm\model\abstracts\dataset;
use fpcm\model\dbal\selectParams;
use fpcm\model\traits\eventModuleEmpty;

/**
 * System config Objekt
 * 
 * @property string $system_version System version
 * @property string $system_email General e-mail address
 * @property string $system_url Frontend url
 * @property string $system_lang System language
 * @property string $system_dtmask Date time mask
 * @property bool   $system_comments_enabled Comment system enabled
 * @property int    $system_session_length ACP session lenght
 * @property bool   $system_mode Frontend mode (0 = iframe, 1= phpinclude)
 * @property string $system_css_path External CSS file path
 * @property bool   $system_show_share Enable share buttons
 * @property bool   $system_share_count Count share button clicks
 * @property string $system_timezone System timezone
 * @property int    $system_cache_timeout System cache timeout
 * @property bool   $system_loader_jquery Load jquery in frontend
 * @property bool   $system_editor article and comment editor
 * @property int    $system_editor_fontsize Default editor fontsize
 * @property bool   $system_maintenance Maintenance mode active
 * @property int    $system_loginfailed_locked Amout of failed logings before account is locked
 * @property int    $system_updates_devcheck Check developer version on update check
 * @property bool   $system_updates_emailnotify E-mail notification if update is available
 * @property int    $system_updates_manual Update check interval for manual update check
 * @property bool   $system_2fa_auth Two factor authentication enabled
 * @property int    $system_trash_cleanup Age of datasets in trash to cleanup
 * @property bool   $system_passcheck_enabled Password check enabled
 * 
 * @property bool   $articles_revisions Enable revision system for articles
 * @property int    $articles_limit Number of articles per page in frontend
 * @property string $articles_template_active Active article list template
 * @property string $article_template_active Active single article view template
 * @property bool   $articles_archive_show Show article archive in frontend
 * @property string $articles_sort Sort by field in frontend
 * @property string $articles_sort_order Sort order in frontend
 * @property bool   $articles_rss Enable RSS feed
 * @property int    $articles_acp_limit Number of articles per page in ACP
 * @property int    $articles_archive_datelimit Hide articles before this date in frontend archive
 * @property int    $articles_revisions_limit Maximum number of kept articles revision
 * @property bool   $articles_link_urlrewrite Enable URL-Rewriting (ID and article will be included within article link)
 * 
 * @property string $comments_template_active Active comment template
 * @property int    $comments_flood Flood protection between two comments
 * @property bool   $comments_email_optional E-mail-address is not mandatory for comments
 * @property bool   $comments_confirm Comments need to be approved before
 * @property string $comments_antispam_question Anti spam captcha question
 * @property string $comments_antispam_answer Anti spam captcha reply
 * @property int    $comments_notify Send notification for new comments to: 0 = General e-mail address, 1 = article auhor, 2 = both)
 * @property int    $comments_markspam_commentcount Mark comments as spam, in case the author has been flagged as spammed before
 * @property bool   $comments_privacy_optin GDPR privacy opt-in
 * 
 * @property int    $file_thumb_size Thumbnail size
 * @property int    $file_list_limit Nubmer of files per page
 * @property bool   $file_subfolders Create subfolder of form YYYY-MM
 * @property string $file_view File manager view
 * @property string $file_cropper_name Image editor file name
 * 
 * @property conf\twitterSettings  $twitter_data Twitter conenctions ettings
 * @property conf\twitterEvents    $twitter_events Events for new twitter posts
 * 
 * @property bool                  $smtp_enabled E-mail-submission via SMTP server
 * @property conf\smtpSettings     $smtp_settings SMTP settings
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class config extends dataset {

    use eventModuleEmpty;

    /**
     * Neue Konfiguration
     * @var array
     */
    protected $newConfig = [];

    /**
     * Benuter-Config bereits eingelesen
     * @var bool
     */
    protected $userConfigSet = false;

    /**
     * Konstruktor
     * @return bool
     */
    function __construct()
    {
        $this->table    = database::tableConfig;
        $this->dbcon    = loader::getObject('\fpcm\classes\database');
        $this->events   = loader::getObject('\fpcm\events\events');
        $this->cache    = loader::getObject('\fpcm\classes\cache');

        if (baseconfig::installerEnabled()) {
            return false;
        }

        $this->init();
        return true;
    }

    /**
     * Speichert neue Konfiguration
     * @param array $newConfig
     */
    public function setNewConfig(array $newConfig)
    {
        $this->newConfig = $newConfig;
    }

    /**
     * not used
     * @return bool
     */
    public function save()
    {
        return false;
    }

    /**
     * not used
     * @return bool
     */
    public function delete()
    {
        return false;
    }

    /**
     * Konfiguration aktualisieren
     * @return bool
     */
    public function update()
    {
        if (!count($this->newConfig)) {
            return false;
        }

        $params = $this->events->trigger('configUpdate', $this->newConfig);

        $data = [];
        $where = [];

        $installedEnabled = baseconfig::installerEnabled();
        foreach ($params as $key => $value) {

            if (!array_key_exists($key, $this->data) && !$installedEnabled) {
                continue;
            }

            $data[$key] = [$value, $key];
            $where[$key] = 'config_name = ?';
        }

        if (!$this->dbcon->updateMultiple($this->table, ['config_value'], $data, $where)) {
            return false;
        }

        $this->refresh();
        return true;
    }

    /**
     * Neuen Config-Key erzeugen
     * @param string $keyname Config option name
     * @param string $keyvalue Config option value
     * @param string $modulekey Module key
     * @return bool
     */
    public function add($keyname, $keyvalue, $modulekey = '')
    {
        if (isset($this->data[$keyname])) {
            return -1;
        }

        $res = $this->dbcon->insert($this->table, (new configItem($keyname, $keyvalue, $modulekey))->getData());
        $this->refresh();

        return $res;
    }

    /**
     * Config Key löschen
     * @param string $keyname Config option name
     * @return bool
     */
    public function remove($keyname)
    {
        if (!isset($this->data[$keyname])) {
            return false;
        }

        $res = $this->dbcon->delete($this->table, "config_name " . $this->dbcon->dbLike() . " ?", [$keyname]);

        $this->refresh();

        return $res;
    }

    /**
     * Überschreibt systemweite Einstellungen mit Benutzer-Einstellungen
     * @return bool
     */
    public function setUserSettings()
    {
        /* @var $user \fpcm\model\users\author */
        $user = loader::stackPull('currentUser');
        if (!$user || !$user->getId() || $this->userConfigSet) {
            return false;
        }

        $userData = $user->getUserMeta();
        if (!is_array($userData)) {
            return false;
        }

        foreach ($userData as $key => $value) {
            $this->data[$key] = $value;
        }

        loader::getObject('\fpcm\classes\language', $this->system_lang, false);
        $this->userConfigSet = true;
        return true;
    }

    /**
     * Wartungsmodus aktivieren
     * @param bool $mode
     * @return bool
     */
    public function setMaintenanceMode($mode)
    {
        $this->newConfig = ['system_maintenance' => (int) $mode];
        return $this->update();
    }

    /**
     * Inittiert Objekt mit Daten aus der Datenbank
     */
    public function init()
    {
        if (baseconfig::installerEnabled()) {
            return false;
        }

        $obj = new \fpcm\model\dbal\selectParams($this->table);
        $obj->setFetchAll(true);
        foreach ($this->dbcon->selectFetch($obj) as $data) {
            $this->data[$data->config_name] = $data->config_value;
        }

        $this->data['twitter_data'] = new conf\twitterSettings($this->data['twitter_data']);
        $this->data['twitter_events'] = new conf\twitterEvents($this->data['twitter_events']);
        $this->data['smtp_settings'] = new conf\smtpSettings($this->data['smtp_settings']);

        return true;
    }

    /**
     * Bereitet Daten für Speicherung in Datenbank vor
     * @return bool
     * @since 3.6
     */
    public function prepareDataSave()
    {
        if (isset($this->newConfig['system_email'])) {
            $this->newConfig['system_email'] = filter_var($this->newConfig['system_email'], FILTER_SANITIZE_EMAIL);
        }

        if (isset($this->newConfig['system_url'])) {
            $this->newConfig['system_url'] = filter_var($this->newConfig['system_url'], FILTER_SANITIZE_URL);
        }

        if (isset($this->newConfig['system_css_path'])) {
            $this->newConfig['system_css_path'] = filter_var($this->newConfig['system_css_path'], FILTER_SANITIZE_URL);
        }

        $classes = $this->newConfig['system_editor_css'] ?? null;
        if (trim($classes)) {
            $classes = explode(PHP_EOL, trim($classes));
            if (count($classes)) {
                $classes = implode(PHP_EOL, array_filter($classes, function ($item) {
                    return (bool) preg_match('/^\.{1}[a-z0-9\_\-]+\{\}$/i', trim($item));
                }));

                $this->newConfig['system_editor_css'] = trim($classes);
            }
            else {
                $this->newConfig['system_editor_css'] = '';
            }
        }

        if (isset($this->newConfig['system_editor'])) {
            $this->newConfig['system_editor'] = base64_decode($this->newConfig['system_editor']);
        }

        if (isset($this->newConfig['twitter_events'])) {
            
            if (!$this->newConfig['twitter_events'] instanceof conf\twitterEvents) {
                $this->newConfig['twitter_events'] = new conf\twitterEvents($this->newConfig['twitter_events'], $this->data['twitter_events']);
            }

            $this->newConfig['twitter_events'] = $this->newConfig['twitter_events']->toJSON();
        }

        if (isset($this->newConfig['twitter_data'])) {
            
            if (!$this->newConfig['twitter_data'] instanceof conf\twitterSettings) {
                $this->newConfig['twitter_data'] = new conf\twitterSettings($this->newConfig['twitter_data'], $this->data['twitter_data']);
            }

            $this->newConfig['twitter_data'] = $this->newConfig['twitter_data']->toJSON();
        }

        if (isset($this->newConfig['smtp_settings']) && is_array($this->newConfig['smtp_settings'])) {
            $this->newConfig['smtp_settings'] = (new conf\smtpSettings($this->newConfig['smtp_settings'], $this->data['smtp_settings'], (bool) $this->newConfig['smtp_enabled']))->toJSON();
        }

        if (isset($this->newConfig['articles_limit'])) {
            $this->newConfig['articles_limit'] = (int) $this->newConfig['articles_limit'];
        }

        if (isset($this->newConfig['articles_acp_limit'])) {
            $this->newConfig['articles_acp_limit'] = (int) $this->newConfig['articles_acp_limit'];
        }

        if (isset($this->newConfig['system_cache_timeout'])) {
            $this->newConfig['system_cache_timeout'] = (int) $this->newConfig['system_cache_timeout'];
        }

        if (isset($this->newConfig['system_session_length'])) {
            $this->newConfig['system_session_length'] = (int) $this->newConfig['system_session_length'];
        }

        if (isset($this->newConfig['comments_flood'])) {
            $this->newConfig['comments_flood'] = (int) $this->newConfig['comments_flood'];
        }

        if (isset($this->newConfig['system_loginfailed_locked'])) {
            $this->newConfig['system_loginfailed_locked'] = (int) $this->newConfig['system_loginfailed_locked'];
        }

        if (isset($this->newConfig['system_passcheck_enabled'])) {
            $this->newConfig['system_passcheck_enabled'] = (int) $this->newConfig['system_passcheck_enabled'];
        }

        if (isset($this->newConfig['comments_markspam_commentcount'])) {
            $this->newConfig['comments_markspam_commentcount'] = (int) $this->newConfig['comments_markspam_commentcount'];
        }

        if (isset($this->newConfig['file_thumb_size'])) {
            $this->newConfig['file_thumb_size'] = (int) $this->newConfig['file_thumb_size'];
        }

        if (isset($this->newConfig['file_list_limit'])) {
            $this->newConfig['file_list_limit'] = (int) $this->newConfig['file_list_limit'];
        }

        if (isset($this->newConfig['file_cropper_name'])) {
            $this->newConfig['file_cropper_name'] = preg_replace('/([^\{\}\_\-a-z])/i', '', $this->newConfig['file_cropper_name']);
        }

        if (isset($this->newConfig['system_updates_devcheck'])) {
            $this->newConfig['system_updates_devcheck'] = (int) $this->newConfig['system_updates_devcheck'];
        }

        if (isset($this->newConfig['articles_revisions_limit'])) {
            $this->newConfig['articles_revisions_limit'] = (int) $this->newConfig['articles_revisions_limit'];
        }

        if (isset($this->newConfig['articles_link_urlrewrite'])) {
            $this->newConfig['articles_link_urlrewrite'] = (int) $this->newConfig['articles_link_urlrewrite'];
        }
            
        if (isset($this->newConfig['comments_privacy_optin'])) {
            $this->newConfig['comments_privacy_optin'] = (int) $this->newConfig['comments_privacy_optin'];
        }
            
        if (isset($this->newConfig['system_trash_cleanup'])) {
            $this->newConfig['system_trash_cleanup'] = (int) $this->newConfig['system_trash_cleanup'];
        }

        if (isset($this->newConfig['articles_archive_datelimit'])) {

            $this->newConfig['articles_archive_datelimit']  = $this->newConfig['articles_archive_datelimit'] && \fpcm\classes\tools::validateDateString($this->newConfig['articles_archive_datelimit'])
                                                            ? strtotime($this->newConfig['articles_archive_datelimit'])
                                                            : 0;
        }

        return true;
    }

    /**
     * Returns config options by module key
     * @param string $key
     * @return array
     * @since 4
     */
    public function getModuleOptions(string $key) : array
    {
        $obj = (new selectParams())->setTable($this->table)->setWhere('modulekey = ?')->setParams([ $key ])->setFetchAll(true);

        $result = $this->dbcon->selectFetch($obj);
        if (!$result) {
            return [];
        }

        $data = [];
        foreach ($result as $row) {
            $data[$row->config_name] = $row->config_value;
        }

        return $data;
    }

    /**
     * Returns minor version string as Number
     * @return string
     * @since 4.1
     */
    public function getVersionNumberString() : string
    {
        preg_match('/([0-9]).([0-9])/i', $this->data['system_version'], $matches);
        return $matches[1].$matches[2];
    }

    /**
     * Disables Twitter connection
     * @return bool
     * @since 4.5-rc2
     */
    final public function disableTwitter(): bool
    {
        $this->twitter_data->reset();
        $this->twitter_events->reset();

        $this->setNewConfig([
            'twitter_data' => $this->twitter_data->toJSON(),
            'twitter_events' => $this->twitter_events->toJSON()
        ]);

        return $this->update();
    }

    /**
     * Config-Refresh
     */
    private function refresh()
    {
        $this->cache->cleanup();
        $this->init();
    }

    /**
     * Array mit Schriftgrößen für Editor
     * @return array
     * @since 3.4
     */
    public static function getDefaultFontsizes()
    {

        $defaultFontsizes = [];
        for ($i = 8; $i <= 16; $i++) {
            $defaultFontsizes[$i . ' pt'] = $i . 'pt';
        }

        return $defaultFontsizes;
    }

    /**
     * Array mit Anzahl-Limits für Artikel in ACP-Liste
     * @return array
     * @since 3.4
     */
    public static function getAcpArticleLimits()
    {
        return [
            10 => 10,
            25 => 25,
            50 => 50,
            75 => 75,
            100 => 100,
            125 => 125,
            150 => 150,
            200 => 200,
            250 => 250
        ];
    }

    /**
     * Array mit Anzahl-Limits für Artikel-Listen
     * @return array
     * @since 3.4
     */
    public static function getArticleLimits()
    {
        return [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            10 => 10,
            15 => 15,
            20 => 20,
            25 => 25,
            30 => 30,
            40 => 40,
            50 => 50
        ];
    }

}
