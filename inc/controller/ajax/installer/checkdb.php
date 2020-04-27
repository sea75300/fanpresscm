<?php

/**
 * AJAX installer database connection check controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\installer;

/**
 * AJAX-Controller zur Prüfung der eingegebenen Datenbank-Zugangsdaten im Installer
 * 
 * @package fpcm\controller\ajax\installer\checkdb
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class checkdb extends \fpcm\controller\abstracts\ajaxController {

    /**
     * Konstruktor
     */
    public function __construct()
    {
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function hasAccess()
    {
        return !\fpcm\classes\baseconfig::dbConfigExists();
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->request = \fpcm\classes\loader::getObject('\fpcm\model\http\request');
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $databaseInfo = $this->request->fromPOST('dbdata');

        try {
            $db = new \fpcm\classes\database($databaseInfo);
        } catch (\PDOException $exc) {
            trigger_error($exc->getMessage());
            exit('0');
        }

        if (!$db->checkDbVersion()) {
            trigger_error('Unsupported database system detected. Installed version is ' . $db->getDbVersion() . ', FanPress CM requires version ' . $db->getRecommendVersion());
            exit('0');
        }

        $db->createDbConfigFile($databaseInfo);

        $crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');
        $crypt->initCrypt();

        \fpcm\classes\security::initSecurityConfig();

        exit('1');
    }

    /**
     * 
     * @return bool
     */
    protected function initPermissionObject(): bool
    {
        return true;
    }

}

?>