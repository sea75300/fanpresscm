<?php
    /**
     * Update check Dashboard Container
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\dashboard;

    /**
     * Update check dashboard container object
     * 
     * @package fpcm\model\dashboard
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class updatecheck extends \fpcm\model\abstracts\dashcontainer {
        
        /**
         * ggf. nötige Container-Berechtigungen
         * @var array
         */
        protected $checkPermissions = array('system' => 'options', 'system' => 'update');
        
        /**
         * Container table content
         * @var array
         */
        protected $tableContent = [];

        /**
         * Ergebnis der System-Update-Prüfung
         * @var bool
         * @since FPCM 3.1.3
         */
        private $systemCheckresult;
        
        /**
         * System-Update-Object
         * @var \fpcm\model\updater\system
         * @since FPCM 3.1.3
         */
        private $systemUpdates;
        
        /**
         * Status, ob automatischer Update-Check fehlgeschlagen ist wenn baseconfig::canConnect = 1 ist
         * @var bool
         * @since FPCM 3.1.3
         */
        private $autoCheckFailed = false;

        /**
         * Konstruktor
         */
        public function __construct() {            
            parent::__construct();   
            
            $this->systemUpdates = new \fpcm\model\updater\system();
            $this->runCheck();

            $this->tableContent[] = '<tr><td colspan="2" class="fpcm-small-text"><p>'.$this->language->translate('UPDATE_VERSIONCHECK_NOTES').'</p><td><tr>';
            
            $this->headline = $this->language->translate('SYSTEM_UPDATE');
            $this->content  = implode(PHP_EOL, array('<table class="fpcm-ui-table fpcm-dashboard-updates fpcm-ui-center">', implode(PHP_EOL, $this->tableContent),'</table>'));
            $this->name     = 'updatecheck';            
            $this->position = 3;
            $this->height   = 0;
        }
        
        /**
         * Check ausführen
         */
        protected function runCheck() {

            $this->getSystemUpdateStatus();
            $this->getModuleUpdateStatus();
        }
        
        /**
         * Liefert System-Update-HTML zurück
         * @since FPCM 3.1.0
         */
        private function getSystemUpdateStatus()
        {
            include_once \fpcm\classes\baseconfig::$versionFile;
            if ($this->config->system_version !== $fpcmVersion) {
                $ctrlParams = ['{{versionlink}}' => \fpcm\classes\tools::getControllerLink('package/sysupdate', ['step' => \fpcm\model\packages\package::FPCMPACKAGE_STEP_UPGRADEDB])];
                $this->renderTable('fa-refresh', 'fpcm-dashboard-updates-versiondbfile', $this->language->translate('UPDATE_VERSIONCECK_FILEDB_ERR',  $ctrlParams));
            }

            $this->systemCheckresult = $this->systemUpdates->checkUpdates();
            if ($this->systemCheckresult === false || $this->systemCheckresult === \fpcm\model\updater\system::SYSTEMUPDATER_FORCE_UPDATE) {
                $iconClass   = 'fa-cloud-download';
                $statusClass = 'fpcm-dashboard-updates-outdated';
                
                $replace = array(
                    '{{versionlink}}' => 'index.php?module=package/sysupdate',
                    '{{version}}'     => $this->systemUpdates->getRemoteData('version')
                );
                $statusText  = $this->language->translate('UPDATE_VERSIONCHECK_NEW', $replace);
            } elseif ($this->systemCheckresult === \fpcm\model\updater\system::SYSTEMUPDATER_FURLOPEN_ERROR) {
                $iconClass   = 'fa-exclamation-triangle';
                $statusClass = 'fpcm-dashboard-updates-checkerror';
                $statusText  = $this->language->translate('UPDATE_NOTAUTOCHECK');
                
                if (\fpcm\classes\baseconfig::canConnect()) {
                    $this->autoCheckFailed = true;
                }
            } else {
                $iconClass   = 'fa-check';
                $statusClass = 'fpcm-dashboard-updates-current';
                $statusText  = $this->language->translate('UPDATE_VERSIONCHECK_CURRENT', array( '{{releaseinfo}}' => $this->systemUpdates->getRemoteData('notice') ? '<a href="'.$this->systemUpdates->getRemoteData('notice').'">Release-Infos</a>' : '', '{{releasmsg}}' => $this->systemUpdates->getRemoteData('message')));
            }

            $this->renderTable($iconClass, $statusClass, $statusText);
        }
        
        /**
         * Liefert Modul-Update-HTML zurück
         * @since FPCM 3.1.0
         */
        private function getModuleUpdateStatus() {
            
            $moduleUpdates = new \fpcm\model\updater\modules();
            $checkRes      = $moduleUpdates->checkUpdates();            
            
            if ($checkRes === true) {
                $iconClass   = 'fa-cloud-download';
                $statusClass = 'fpcm-dashboard-updates-outdated';
                $statusText  = $this->language->translate('UPDATE_MODULECHECK_NEW');
            } elseif ($checkRes === \fpcm\model\updater\system::SYSTEMUPDATER_FURLOPEN_ERROR) {
                $iconClass   = 'fa-exclamation-triangle';
                $statusClass = 'fpcm-dashboard-updates-checkerror';
                $statusText  = $this->language->translate('UPDATE_MODULECHECK_FAILED');         
            } else {
                $iconClass   = 'fa-check';
                $statusClass = 'fpcm-dashboard-updates-current';
                $statusText  = $this->language->translate('UPDATE_MODULECHECK_CURRENT');
            }

            $this->renderTable($iconClass, $statusClass, $statusText);
        }
        
        /**
         * Gibt benötigte Javascript-Variablen zurück
         * @see \fpcm\model\interfaces\dashcontainer::getJavascriptVars()
         * @return array
         */
        public function getJavascriptVars() {
            
            if (!$this->autoCheckFailed) {
                return [];
            }
            
            return array(
                'fpcmManualCheckUrl'      => $this->systemUpdates->getManualCheckAddress(),
                'fpcmManualCheckHeadline' => $this->language->translate('HL_PACKAGEMGR_SYSUPDATES')
            );
        }
        
        /**
         * Gibt Liste mit zu Variablen zurück, welche an Dashboard-Controller-View übergeben werden sollen
         * @see \fpcm\model\interfaces\dashcontainer::getControllerViewVars()
         * @return array
         */
        public function getControllerViewVars() {
            
            if (!$this->autoCheckFailed) {
                return [];
            }
            
            return array(
                'includeManualCheck' => true,
                'autoDialog'         => false
            );
        }
        
        /**
         * Tabellenzeile rendern
         * @param string $iconClass
         * @param string $statusClass
         * @param string $statusText
         * @since FPCM 3.1.0
         */
        private function renderTable($iconClass, $statusClass, $statusText) {
            $content  = '<tr><td>';
            $content .= '<span class="fa-stack fa-fw fa-2x '.$statusClass.'"><span class="fa fa-square fa-stack-2x"></span><span class="fa '.$iconClass.' fa-stack-1x fa-inverse"></span></span>';            
            $content .= '</td><td>';
            $content .= $statusText;
            $content .= '</td></tr>';
            $this->tableContent[] = $content;
        }
    }