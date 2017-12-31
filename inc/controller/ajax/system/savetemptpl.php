<?php

    namespace fpcm\controller\ajax\system;

    /**
     * AJAX save template preview code
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\controller\ajax\system\cronasync
     * @since FPCM 3.4
     */
    class savetemptpl extends \fpcm\controller\abstracts\ajaxController {

        use \fpcm\controller\traits\system\templatepreview;
        
        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
            $this->checkPermission = array('system' => 'templates');
        }
        
        /**
         * Request-Handler
         * @return bool
         */
        public function request() {
            return $this->session->exists();
        }
        
        /**
         * Controller-Processing
         */
        public function process() {

            $tplId   = $this->getRequestVar('tplid', [9]);
            $content = $this->getRequestVar('content', [7,6]);
            
            $template = $this->getTemplateById($tplId);

            file_put_contents($template->getFullpath(), '');

            $template->setContent($content);
            $template->save();

        }
        
    }
?>