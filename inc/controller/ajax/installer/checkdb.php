<?php
    /**
     * AJAX installer database connection check controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
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
         * @return boolean
         */
        public function __construct() {
            return true;
        }
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {
            if (\fpcm\classes\baseconfig::dbConfigExists()) return false;
            
            return true;
        }
        
        /**
         * Controller-Processing
         */
        public function process() {

            $databaseInfo = $this->getRequestVar('dbdata');

            try {
                $db = new \fpcm\classes\database($databaseInfo);                
            } catch (\PDOException $exc) {
                trigger_error($exc->getMessage());
                die('0');
            }
            
            if (!$db->checkDbVersion()) {
                trigger_error('Unsupported database system detected. Installed version is '.$db->getDbVersion().', FanPress CM requires version '.$db->getRecommendVersion());
                die('0');
            }

            $db->createDbConfigFile($databaseInfo);

            $crypt = new \fpcm\classes\crypt();
            $crypt->initCrypt();
            
            \fpcm\classes\security::initSecurityConfig();
            
            die('1');
        }

    }
?>