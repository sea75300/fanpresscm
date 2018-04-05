<?php

/**
 * Update check Dashboard Container
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
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
     * Status if update is forced by repository
     * @var bool
     * @since FPCM 4
     */
    private $forceUpdate = false;

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'updatecheck';
    }

    /**
     * 
     * @return string
     */
    public function getContent()
    {
        $this->getSystemUpdateStatus();
        $this->getModuleUpdateStatus();

        $this->tableContent[] = implode(PHP_EOL, [
            '<div class="row no-gutters fpcm-ui-font-small fpcm-ui-padding-lg-top">',
            '<div class="col-12 align-self-center">'.$this->language->translate('UPDATE_VERSIONCHECK_NOTES').'</div>',
            '</div>'
        ]);

        return '<div class="fpcm-dashboard-updates fpcm-ui-center">'.implode(PHP_EOL, $this->tableContent).'</div>';
    }

    /**
     * 
     * @return array
     */
    public function getPermissions()
    {
        return ['system' => 'options', 'system' => 'update'];
    }

    /**
     * Return container headline
     * @return string
     */
    public function getHeadline()
    {
        return 'SYSTEM_UPDATE';
    }

    /**
     * 
     * @return int
     */
    public function getPosition()
    {
        return 3;
    }

    /**
     * 
     * @return boolean
     */
    protected function initObjects()
    {
        $this->systemUpdates = new \fpcm\model\updater\system();
        return true;
    }
    
    /**
     * Liefert System-Update-HTML zurück
     * @since FPCM 3.1.0
     */
    private function getSystemUpdateStatus()
    {
        if ($this->config->system_version !== \fpcm\classes\baseconfig::getVersionFromFile()) {
            $button = (string) (new \fpcm\view\helper\linkButton('updater'))->setText('PACKAGES_UPDATE')->setIcon('sync')->setUrl(\fpcm\classes\tools::getFullControllerLink('package/sysupdate', ['update-db' => 1]));
            $this->renderTable('code-branch', 'fpcm-dashboard-updates-versiondbfile', $this->language->translate('UPDATE_VERSIONCECK_FILEDB_ERR', [ '{{btn}}' => $button ]));            
        }

        $this->systemCheckresult = $this->systemUpdates->updateAvailable();
        if ($this->systemCheckresult === true || $this->systemCheckresult === \fpcm\model\updater\system::FORCE_UPDATE) {

            $iconClass = 'cloud-download-alt';
            $statusClass = 'fpcm-dashboard-updates-outdated';

            $statusText = $this->language->translate('UPDATE_VERSIONCHECK_NEW', [
                '{{btn}}' => (string) (new \fpcm\view\helper\linkButton('startUpdate'))->setText('PACKAGES_UPDATE')->setIcon('sync')->setUrl(\fpcm\classes\tools::getFullControllerLink('package/sysupdate')),
                '{{version}}' => $this->systemUpdates->version                
            ]);

            $this->forceUpdate = $this->systemCheckresult === \fpcm\model\updater\system::FORCE_UPDATE ? true : false;

        } elseif ($this->systemCheckresult === \fpcm\model\abstracts\remoteModel::FURLOPEN_ERROR) {
            $iconClass = 'exclamation-triangle';
            $statusClass = 'fpcm-dashboard-updates-checkerror';
            $statusText = $this->language->translate('UPDATE_NOTAUTOCHECK', [
                '{{btn}}' => (string) (new \fpcm\view\helper\linkButton('chckmanual'))->setText('PACKAGES_MANUALCHECK')->setIcon('external-link-square-alt ')->setUrl(\fpcm\classes\baseconfig::$updateServerManualLink)->setTarget('_blank'),
            ]);
        } else {
            $iconClass = 'check';
            $statusClass = 'fpcm-dashboard-updates-current';
            $statusText = $this->language->translate('UPDATE_VERSIONCHECK_CURRENT');
        }

        $this->renderTable($iconClass, $statusClass, $statusText);
    }

    /**
     * Liefert Modul-Update-HTML zurück
     * @since FPCM 3.1.0
     */
    private function getModuleUpdateStatus()
    {
        return true;
        
//        $moduleUpdates = new \fpcm\model\updater\modules();
//        $checkRes = $moduleUpdates->checkUpdates();

        $checkRes = false;
        
        if ($checkRes === true) {
            $iconClass = 'cloud-download-alt';
            $statusClass = 'fpcm-dashboard-updates-outdated';
            $statusText = $this->language->translate('UPDATE_MODULECHECK_NEW');
        } elseif ($checkRes === \fpcm\model\abstracts\remoteModel::FURLOPEN_ERROR) {
            $iconClass = 'exclamation-triangle';
            $statusClass = 'fpcm-dashboard-updates-checkerror';
            $statusText = $this->language->translate('UPDATE_MODULECHECK_FAILED');
        } else {
            $iconClass = 'check';
            $statusClass = 'fpcm-dashboard-updates-current';
            $statusText = $this->language->translate('UPDATE_MODULECHECK_CURRENT');
        }

        $this->renderTable($iconClass, $statusClass, $statusText);
    }

    /**
     * 
     * @see \fpcm\model\interfaces\dashcontainer::getJavascriptVars()
     * @return array
     */
    public function getJavascriptVars()
    {
        return [
            'openUpdateCheckUrl' => $this->systemUpdates->checkManual(),
            'forceUpdate' => $this->forceUpdate
        ];
    }

    /**
     * 
     * @see \fpcm\model\interfaces\dashcontainer::getJavascriptLangVars()
     * @return array
     */
    public function getJavascriptLangVars()
    {
        return ['HL_PACKAGEMGR_SYSUPDATES'];
    }

    /**
     * Tabellenzeile rendern
     * @param string $iconClass
     * @param string $statusClass
     * @param string $statusText
     * @since FPCM 3.1.0
     */
    private function renderTable($iconClass, $statusClass, $statusText)
    {
        $this->tableContent[] = implode(PHP_EOL, [
            '<div class="row no-gutters">',
            '<div class="col-3">'.(new \fpcm\view\helper\icon($iconClass.' fa-inverse'))->setSize('2x')->setClass($statusClass)->setStack('square').'</div>',
            '<div class="col-9 align-self-center">'.$statusText.'</div>',
            '</div>'
        ]);
    }

}
