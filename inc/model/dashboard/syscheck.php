<?php
    /**
     * System check Dashboard Container
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\dashboard;

    /**
     * System check dashboard container object
     * 
     * @package fpcm\model\dashboard
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class syscheck extends \fpcm\model\abstracts\dashcontainer {
        
        /**
         * ggf. nötige Container-Berechtigungen
         * @var array
         */
        protected $checkPermissions = array('system' => 'options');
        
        /**
         * Table container
         * @var array
         */
        protected $tableContent = [];

        /**
         * Konstruktor
         */
        public function __construct() {

            $this->cacheName   = 'syscheck';
            $this->cacheModule = self::CACHE_M0DULE_DASHBOARD;

            parent::__construct();   
            
            $this->runCheck();

            $this->headline = $this->language->translate('SYSTEM_CHECK');
            $this->content  = implode(PHP_EOL, array('<table class="fpcm-ui-table fpcm-small-text2" style="overflow:auto;">', implode(PHP_EOL, $this->tableContent),'</table>'));
            $this->name     = 'syscheck';            
            $this->position = 5;
            $this->height   = 1;
        }
        
        /**
         * Check ausführen
         */
        protected function runCheck() {
            
            $sysCheckAction = new \fpcm\controller\ajax\system\syscheck();
            $rows = $sysCheckAction->processCli();

            $this->tableContent[] = '<tr><td class="fpcm-ui-center"><a class="fpcm-ui-button fpcm-ui-margin-icon fpcm-syscheck-btn" href="index.php?module=system/options&syscheck=1">'.$this->language->translate('SYSCHECK_COMPLETE').'</a></td></tr>';
            $this->tableContent[] = '<tr><td class="fpcm-td-spacer" style="padding-bottom:0.5em;"></td></tr>';

            $options = array_slice($rows, 16, 2);
            foreach ($options as $description => $data) {
                $checkres             = $this->boolToText2($data['result']);
                $this->tableContent[] =  "<tr><td>{$checkres} {$description}</td></tr>";
            }

            $folders = array_slice($rows, -13);
            foreach ($folders as $description => $data) {
                $checkres             = $this->boolToText($data['result']);
                $this->tableContent[] =  "<tr><td>{$checkres} {$description}</td></tr>";
            }

        }
        
        /**
         * bool nach text beschreibbar/ nicht beschreibbar
         * @param bool $value
         * @return string
         */
        protected function boolToText($value) {
            return ($value) ? '<span class="fa fa-check-square fpcm-ui-booltext-yes" title="'.$this->language->translate('GLOBAL_WRITABLE').'"></span>' : '<span class="fa fa-minus-square fpcm-ui-booltext-no" title="'.$this->language->translate('GLOBAL_NOT_WRITABLE').'"></span>';
        }
        
        /**
         * bool nach Text ja/ nein
         * @param bool $value
         * @return string
         */
        protected function boolToText2($value) {
            return ($value) ? '<span class="fa fa-check-square fpcm-ui-booltext-yes" title="'.$this->language->translate('GLOBAL_YES').'"></span>' : '<span class="fa fa-minus-square fpcm-ui-booltext-no" title="'.$this->language->translate('GLOBAL_NO').'"></span>';
        }
        
    }