<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system\check;

/**
 * System check runner Objekt
 *
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-rc3
 */
final class check extends \fpcm\model\abstracts\staticModel {

    /**
     * HTML output check
     * @var bool
     */
    private bool $html = true;

    /**
     * Check options result data
     * @var array
     */
    private array $result = [];

    /**
     * Check folders result data
     * @var array
     */
    private array $folders = [];

    /**
     * Constructor
     * @param bool $html
     */
    public function __construct(bool $html = true)
    {
        parent::__construct();
        $this->html = $html;
    }

        /**
     * System-Check-Optionen ermitteln
     * @return array
     */
    public function runCheck()
    {
        $this->perform();
        
        $result = $this->getFullResult();

        $ev = $this->events->trigger('runSystemCheck', $result);
        if (!$ev->getSuccessed()) {
            trigger_error(sprintf("Event runSystemCheck failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return $result;
        }

        if (!$ev->getContinue()) {
            trigger_error(sprintf("Event runSystemCheck failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return [];
        }

        return $ev->getData();
    }


    /**
     * System-Check ausführen
     * @return array
     */
    public function perform()
    {

        $loadedExtensions = array_map('strtolower', get_loaded_extensions());

        if (!\fpcm\classes\baseconfig::installerEnabled() && \fpcm\classes\baseconfig::dbConfigExists()) {

            $updater = \fpcm\model\updater\system::getInstance();
            $hasUpdates = $updater->updateAvailable();

            $label = $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_FPCMVERSION', ['value' => $updater->version ?? $this->language->translate('GLOBAL_NOTFOUND')]);

            $option = new option(
                current: $this->config->system_version,
                helplink: 'https://nobody-knows.org/fanpress-cm/',
                result: !$hasUpdates,
                label: $label
            );

            if ($hasUpdates) {

                if (\fpcm\classes\baseconfig::isCli()) {
                    $option->setNotice('You may run       : php '.\fpcm\classes\dirs::getFullDirPath('fpcmcli.php').' pkg --upgrade system');
                }
                elseif ($this->permissions->system->update) {
                    $btn = new \fpcm\view\helper\updateButton('startUpdate');
                    $btn->setUpdater($updater);
                    $option->setActionButton($btn);
                }

            }

            $this->result['update'] = $option;

        }

        $this->result['phpversion'] = new option(
            current: phpversion(),
            helplink: 'http://php.net/',
            result: version_compare(phpversion(), FPCM_PHP_REQUIRED, '>='),
            label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_PHPVERSION', ['value' => FPCM_PHP_REQUIRED])
        );

        $recomVal   = 64;
        $curVal     = \fpcm\classes\baseconfig::memoryLimit();
        $this->result['memory'] = new option(
            current: $curVal . ' MiB',
            helplink: 'http://php.net/manual/info.configuration.php',
            result: ($curVal >= $recomVal ? true : false),
            optional: true,
            label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_PHPMEMLIMIT', [ 'value' => $recomVal . ' MiB'])
        );

        $recomVal   = 10;
        $curVal     = ini_get('max_execution_time');
        $this->result['max_execution_time'] = new option(
            current: $curVal . 'sec',
            helplink: 'http://php.net/manual/info.configuration.php',
            result: ($curVal >= $recomVal ? true : false),
            optional: true,
            label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_PHPMAXEXECTIME', ['value' => $recomVal . 'sec'])
        );

        $dbDrivers      = \PDO::getAvailableDrivers();
        $resultMySql    = in_array(\fpcm\classes\database::DBTYPE_MYSQLMARIADB, $dbDrivers);
        $resultPgSql    = in_array(\fpcm\classes\database::DBTYPE_POSTGRES, $dbDrivers);
        $sqlhelp        = 'http://php.net/manual/pdo.getavailabledrivers.php';

        $this->result['mysql'] = new option(
            current: $this->toBoolText($resultMySql),
            helplink: $sqlhelp,
            result: $resultMySql,
            optional: (!$resultMySql && $resultPgSql ? 1 : 0),
            label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_DBDRV_MYSQL', ['value' => 'true'])
        );

        $this->result['psql'] = new option(
            current: $this->toBoolText($resultPgSql),
            helplink: $sqlhelp,
            result: $resultPgSql,
            optional: ($resultMySql ? 1 : 0),
            label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_DBDRV_PGSQL', ['value' => 'true'])
        );

        $db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        if (\fpcm\classes\baseconfig::dbConfigExists() && is_object($db)) {

            $recommend = implode('/', array_intersect($dbDrivers, array_keys(\fpcm\classes\database::$supportedDBMS)));

            $this->result['dbtype'] = new option(
                current: $db->getDbtype(),
                helplink: 'http://php.net/manual/pdo.getavailabledrivers.php',
                result: true,
                label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_DBDRV_ACTIVE', ['value' => $recommend])
            );

            $this->result['dbversion'] = new option(
                current: $db->getDbVersion(),
                helplink: 'http://php.net/manual/pdo.getattribute.php',
                result: $db->checkDbVersion(),
                label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_DBVERSION', ['value' => $db->getRecommendVersion()])
            );
        }

        $this->result['cache'] = new option(
            current: $this->language->translate(\fpcm\classes\cache::getCacheBackendName()),
            helplink: 'https://sea75300.github.io/fanpresscm/',
            result: true,
            label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_CACHE') . ' (' . $this->language->translate('GLOBAL_OPTIONAL') . ')'
        );

        $current = in_array('pdo', $loadedExtensions) && in_array('pdo_mysql', $loadedExtensions);
        $this->result['pdo'] = new option(
            current: 'true',
            helplink: 'http://php.net/manual/en/class.pdo.php',
            result: (true && $current),
            label: 'PHP Data Objects (PDO)'
        );

        $current = (CRYPT_SHA256 == 1 ? true : false);
        $current = $current && in_array(\fpcm\classes\security::defaultHashAlgo, hash_algos());
        $this->result['sha256'] = new option(
            current: 'true',
            helplink: 'http://php.net/manual/function.hash-algos.php',
            result: (true && $current),
            label: 'SHA256 Hash Algorithm'
        );

        $current = in_array('gd', $loadedExtensions);
        $this->result['gd'] = new option(
            current: 'true',
            helplink: 'http://php.net/manual/book.image.php',
            result: (true && $current),
            label: 'GD Lib'
        );

        $current = in_array('json', $loadedExtensions);
        $this->result['json'] = new option(
            current: 'true',
            helplink: 'http://php.net/manual/book.json.php',
            result: (true && $current),
            label: 'JSON'
        );

        $current = in_array('xml', $loadedExtensions) && in_array('simplexml', $loadedExtensions) && class_exists('DOMDocument');
        $this->result['xml'] = new option(
            current: 'true',
            helplink: 'http://php.net/manual/class.simplexmlelement.php',
            result: (true && $current),
            label: 'XML, SimpleXML, DOMDocument'
        );

        $current = in_array('zip', $loadedExtensions);
        $this->result['zip'] = new option(
            current: 'true',
            helplink: 'http://php.net/manual/class.ziparchive.php',
            result: (true && $current),
            label: 'ZipArchive'
        );

        $current = in_array('openssl', $loadedExtensions) && defined('OPENSSL_ALGO_SHA512');
        $this->result['openssl'] = new option(
            current: 'true',
            helplink: 'http://php.net/manual/book.openssl.php',
            result: (true && $current),
            label: 'OpenSSL'
        );

        $current = in_array('curl', $loadedExtensions);
        $this->result['curl'] = new option(
            current: 'true',
            helplink: 'http://php.net/manual/book.curl.php',
            result: (true && $current),
            optional: true,
            label: 'cURL (' . $this->language->translate('GLOBAL_OPTIONAL') . ')'
        );

        $externalCon = \fpcm\classes\baseconfig::canConnect();
        $this->result['allow_url_fopen'] = new option(
            current: $this->toBoolText($externalCon),
            helplink: 'http://php.net/manual/filesystem.configuration.php#ini.allow-url-fopen',
            result: (true && $externalCon),
            label: 'allow_url_fopen = 1'
        );

        $https = \fpcm\classes\baseconfig::canHttps();
        $this->result['https'] = new option(
            current: $https ? 'true' : 'false',
            helplink: 'http://php.net/manual/reserved.variables.server.php',
            result: (true && $https),
            optional: true,
            label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_HTTPS') . ' (' . $this->language->translate('GLOBAL_OPTIONAL') . ')'
        );

        $opcache = \fpcm\classes\baseconfig::hasOpcache();
        $this->result['opcache'] = new option(
                current: $this->toBoolText($opcache),
                helplink: 'https://www.php.net/manual/de/book.opcache.php',
                result: (true && $opcache),
                optional: true,
                label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_OPCACHE') . ' (' . $this->language->translate('GLOBAL_OPTIONAL') . ')'
            );


        $memcache = \fpcm\classes\baseconfig::hasMemcache();
        $this->result['memcache']
            = new option(
                current: $this->toBoolText($memcache ),
                helplink: 'https://www.php.net/manual/de/book.memcache.php',
                result: (true && $memcache),
                optional: true,
                label: $this->language->translate('SYSTEM_OPTIONS_SYSCHECK_MEMCACHE') . ' (' . $this->language->translate('GLOBAL_OPTIONAL') . ')'
            );


        $dirs = $this->getCheckFolders();

        $pattern = $this->html ? '%s <span class="text-secondary">%s</span>' : '%s - %s';

        array_walk($dirs, function($folderPath, $description) use (&$checkOptions, $pattern) {

            $current = is_writable($folderPath);
            $pathOutput = \fpcm\model\files\ops::removeBaseDir($folderPath, true);

            $lVar = 'SYSCHECK_FOLDER_' . strtoupper(basename($folderPath));
            if (!$this->language->exists($lVar)) {
                $lVar = 'GLOBAL_UNKNOWN';
            }

            $opt = new option(
                current: $this->toBoolText($current),
                result: (true && $current),
                isFolder: true,
                label: sprintf($pattern, $this->language->translate($lVar), $pathOutput)
            );

            $this->folders[$folderPath] = $opt;
        });
    }

    /**
     * Return check result
     * @param array $include
     * @return array
     */
    public function getOptionsResult(array $include = []) : array
    {
        if (!count($include)) {
            return $this->result;
        }

        return array_intersect_key($this->result, $include);
    }

    /**
     * Return folders result
     * @return array
     */
    public function getFolderResult() : array
    {
        return $this->folders;
    }

    /**
     * Return complete result
     * @return array
     */
    public function getFullResult() : array
    {
        return array_merge_recursive($this->result, $this->folders);
    }

    /**
     * Check folders
     * @return array
     */
    public function getCheckFolders() : array
    {
        return glob(\fpcm\classes\dirs::getDataDirPath('', '*'), GLOB_ONLYDIR);
    }

    /**
     * Convert bool value to bool text
     * @param bool $val
     * @return string
     */
    private function toBoolText(bool $val) : string
    {
        return $val ? 'true' : 'false';
    }

    /**
     *
     * @return bool
     */
    public function submitStats() : bool
    {
        $this->perform();
        
        $options = '';
        $folders = '';
        
        /* @var $opt \fpcm\model\system\check\option */
        foreach ($this->getOptionsResult() as $opt) {
            $options .= sprintf('<li>%s : %s -> %s</li>', $opt->getLabel(), $opt->getCurrent(), $opt->getResult());
        }

        /* @var $folder \fpcm\model\system\check\option */
        foreach ($this->getFolderResult() as $folder) {
            $folders .= sprintf('<li>%s : %s</li>', $folder->getLabel(), $folder->getResult() ? 'w' : 'nw');
        }

        $email = new \fpcm\classes\email(
            to: 'sea75300@yahoo.de',
            subject: 'FanPress CM Stats',
            html: true
        );

        $email->fromTemplate('stats', [
            \fpcm\classes\tools::getHash(\fpcm\classes\dirs::getRootUrl()),
            $options,
            $folders
        ]);

        return $email->submit();
    }

}
