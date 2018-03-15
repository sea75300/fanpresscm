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
     * Status, ob automatischer Update-Check fehlgeschlagen ist wenn baseconfig::canConnect = 1 ist
     * @var bool
     * @since FPCM 3.1.3
     */
    private $autoCheckFailed = false;

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
        $this->systemUpdates = new \fpcm\model\updater\system();
        $this->getSystemUpdateStatus();
        $this->getModuleUpdateStatus();

//        $this->tableContent[] = '<div class="row fpcm-ui-font-small"><div class="col-4"><p>' . $this->language->translate('UPDATE_VERSIONCHECK_NOTES') . '</p><div><div>';
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
     * Liefert System-Update-HTML zurück
     * @since FPCM 3.1.0
     */
    private function getSystemUpdateStatus()
    {
        include_once \fpcm\classes\baseconfig::$versionFile;
        if ($this->config->system_version !== $fpcmVersion) {
            $ctrlParams = ['{{versionlink}}' => \fpcm\classes\tools::getControllerLink('package/sysupdate', [
                'step' => \fpcm\model\packages\package::FPCMPACKAGE_STEP_UPGRADEDB
            ])];
            $this->renderTable('refresh', 'fpcm-dashboard-updates-versiondbfile', $this->language->translate('UPDATE_VERSIONCECK_FILEDB_ERR', $ctrlParams));
        }

        $this->systemCheckresult = $this->systemUpdates->checkUpdates();
        if ($this->systemCheckresult === false || $this->systemCheckresult === \fpcm\model\updater\system::SYSTEMUPDATER_FORCE_UPDATE) {
            $iconClass = 'cloud-download';
            $statusClass = 'fpcm-dashboard-updates-outdated';

            $replace = array(
                '{{versionlink}}' => 'index.php?module=package/sysupdate',
                '{{version}}' => $this->systemUpdates->getRemoteData('version')
            );
            $statusText = $this->language->translate('UPDATE_VERSIONCHECK_NEW', $replace);
        } elseif ($this->systemCheckresult === \fpcm\model\updater\system::SYSTEMUPDATER_FURLOPEN_ERROR) {
            $iconClass = 'exclamation-triangle';
            $statusClass = 'fpcm-dashboard-updates-checkerror';
            $statusText = $this->language->translate('UPDATE_NOTAUTOCHECK');

            if (\fpcm\classes\baseconfig::canConnect()) {
                $this->autoCheckFailed = true;
            }
        } else {
            $iconClass = 'check';
            $statusClass = 'fpcm-dashboard-updates-current';
            $statusText = $this->language->translate('UPDATE_VERSIONCHECK_CURRENT', [
                '{{releaseinfo}}' => $this->systemUpdates->getRemoteData('notice') ? '<a href="' . $this->systemUpdates->getRemoteData('notice') . '">Release-Infos</a>' : '',
                '{{releasmsg}}' => $this->systemUpdates->getRemoteData('message')
            ]);
        }

        $this->renderTable($iconClass, $statusClass, $statusText);
    }

    /**
     * Liefert Modul-Update-HTML zurück
     * @since FPCM 3.1.0
     */
    private function getModuleUpdateStatus()
    {
        $moduleUpdates = new \fpcm\model\updater\modules();
        $checkRes = $moduleUpdates->checkUpdates();

        if ($checkRes === true) {
            $iconClass = 'cloud-download';
            $statusClass = 'fpcm-dashboard-updates-outdated';
            $statusText = $this->language->translate('UPDATE_MODULECHECK_NEW');
        } elseif ($checkRes === \fpcm\model\updater\system::SYSTEMUPDATER_FURLOPEN_ERROR) {
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
            'manualCheckUrl' => $this->autoCheckFailed ? $this->systemUpdates->getManualCheckAddress() : false,
            'autoDialog' => true
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
     * Gibt Liste mit zu Variablen zurück, welche an Dashboard-Controller-View übergeben werden sollen
     * @see \fpcm\model\interfaces\dashcontainer::getControllerViewVars()
     * @return array
     */
    public function getControllerViewVars()
    {
        return ['includeManualCheck' => $this->autoCheckFailed ? true : false];
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
