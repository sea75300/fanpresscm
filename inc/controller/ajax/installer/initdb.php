<?php

/**
 * AJAX installer database init controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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
     * Current value
     * @var int
     */
    protected $current = 0;

    /**
     * Next value
     * @var bool
     */
    protected $next = false;

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
        $this->request = new \fpcm\model\http\request();
        $this->response = new \fpcm\model\http\response();

        $this->current = $this->request->fromPOST('current', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $this->next = (bool) $this->request->fromPOST('next', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);        
        
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $files = \fpcm\classes\database::getTableFiles();

        if ($this->current >= count($files)) {
            $this->response->setReturnData([
                'current' => count($files),
                'next' => 0
            ])->fetch();
        }

        $db = new \fpcm\classes\database(false, true);

        $progress = new \fpcm\model\system\progress(function (&$data, &$current, $next, &$stop) use ($files, $db) {

            if ($current == count($files)) {
                $stop = true;
                return false;
            }
            
            if (!isset($files[$current])) {
                $data['msg'] = new \fpcm\view\message('INSTALLER_CREATETABLES_ERROR', \fpcm\view\message::ICON_ERROR);
                trigger_error('Invalid file index '.$current.'!');
                $stop = true;
                $next = false;
                return false;
            }

            $filename = $files[$current];
            $basename = substr(basename($filename, '.yml'), 2);

            if (!file_exists($filename) || substr($filename, -4) !== '.yml') {
                trigger_error('Invalid database file detected, '.$filename.' is not a YaTDL file.');
                usleep(100000);
                $data['html'][] = [
                    'tab' => $basename,
                    'icon' => 'ban',
                    'class' => 'fpcm-ui-important-text'
                ];
                return false;
            }
            
            $current++;
            if (!$db->execYaTdl($filename)) {
                usleep(100000);
                $data['html'][] = [
                    'tab' => $basename,
                    'icon' => 'ban',
                    'class' => 'fpcm-ui-important-text'
                ];
                return true;
            }

            usleep(100000);
            $data['html'][] =  [
                'tab' => $basename,
                'icon' => 'check',
                'class' => ''
            ];
            
            return true;
        });

        $progress->setCurrent($this->current);
        $progress->process();
        $progress->setNext( $progress->getCurrent() < count($files) && !$progress->getStop() );
        
        $this->response->setReturnData($progress)->fetch();
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