<?php
    /**
     * AJAX reload package list controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\modules;
    
    /**
     * AJAX-Controller der einen Reload der Paketliste im Modul-Manager durchführt
     * 
     * @package fpcm\controller\ajax\modules\loadpkglist
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class loadpkglist extends \fpcm\controller\abstracts\ajaxController {

        use \fpcm\controller\traits\modules\moduleactions;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
        }

        /**
         * Controller-Processing
         */
        public function process() {
            
            if (!parent::process()) return false;
            
            $this->cache->cleanup();

            $updater = new \fpcm\model\updater\modules();            
            $updater->checkUpdates(true);
            
        }

    }
?>
