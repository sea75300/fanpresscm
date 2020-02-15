<?php

/**
 * AJAX installer database init controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\installer;

/**
 * AJAX-Controller zur Initialisierung der Datenbank im Installer
 * 
 * @package fpcm\controller\ajax\installer\initdb
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class initdb extends \fpcm\controller\abstracts\ajaxController {

    /**
     * Datenbank-Config-Datei
     * @var string
     */
    protected $filename;

    /**
     * Konstruktor
     * @return bool
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
        if (!\fpcm\classes\baseconfig::dbConfigExists() && !\fpcm\classes\baseconfig::installerEnabled()) {
            return false;
        }
        
        return true;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->filename = base64_decode(str_rot13($this->getRequestVar('file', [
            \fpcm\classes\http::FILTER_BASE64DECODE
        ])));

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (!file_exists($this->filename) || strpos($this->filename, '.yml') === false) {
            exit('0');
        }

        $db = new \fpcm\classes\database(false, true);
        if ($db->execYaTdl($this->filename)) {
            exit('1');
        }

        exit('0');
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