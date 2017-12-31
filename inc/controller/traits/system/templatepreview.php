<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\controller\traits\system;
    
    /**
     * System check trait
     * 
     * @package fpcm\controller\traits\system.syscheck
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    trait templatepreview {
        
        /**
         * 
         * @param type $tplId
         * @return bool|\fpcm\model\pubtemplates\template
         */
        protected function getTemplateById($tplId) {

            $filename = '_preview'.$tplId;

            switch ($tplId) {
                case 1 :
                    return new \fpcm\model\pubtemplates\article($filename);
                case 2 :
                    return new \fpcm\model\pubtemplates\article($filename);
                case 3 :
                    return new \fpcm\model\pubtemplates\comment($filename);
                case 4 :
                    return new \fpcm\model\pubtemplates\commentform($filename);
                case 5 :
                    return new \fpcm\model\pubtemplates\latestnews($filename);
            }
            
            return false;
            
        }
        
    }
?>