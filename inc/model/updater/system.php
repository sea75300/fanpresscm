<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\updater;

/**
 * System updater object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * 
 * @property string
 * @property string $version
 * @property bool $force
 * @property string $url
 * @property string $signature
 * @property string $phpversion
 * @property string $release
 */
final class system extends \fpcm\model\abstracts\staticModel {

    /**
     * Status, dass Update erzwungen wird
     */
    const FORCE_UPDATE = 1001;

    /**
     *
     * @var \fpcm\model\files\fileOption
     */
    private $fileOption;

    /**
     * Initialisiert System Updater
     * @param int $init
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileOption = new \fpcm\model\files\fileOption(\fpcm\model\packages\repository::FOPT_UPDATES);

        include_once \fpcm\classes\loader::libGetFilePath('spyc/Spyc.php');
        $foptData = \Spyc::YAMLLoadString($this->fileOption->read());
        
        $currentVersionComplete = $this->config->system_version;
        $currentVersionMinor    = implode('.', array_slice(explode('.', $currentVersionComplete), 0, 2));

        if ($this->config->system_updates_devcheck) {
            $currentVersionComplete .= '-dev';
            $currentVersionMinor .= '-dev';
        }

        if (isset($foptData[$currentVersionComplete])) {
            $this->data = $foptData[$currentVersionComplete];
            return true;
        }

        if (isset($foptData[$currentVersionMinor]) ) {
            $this->data = $foptData[$currentVersionMinor];
            return true;
        }

        $this->data = $foptData['default'];
        return true;
    }

    /**
     * Prüft ob Updates verfügbar sind
     * @return boolean
     */
    public function updateAvailable()
    {
        $newVersion = version_compare($this->data['version'], $this->config->system_version, '>');
        if ($newVersion && isset($this->data['phpversion']) && version_compare(phpversion(), $this->data['phpversion'], '<')) {
            fpcmLogSystem('FanPress CM ' . $this->data['version'] . ' is available, but requires PHP ' . $this->data['phpversion'] . ' or higher.');
            return true;
        }

        if ($newVersion && $this->data['force']) {
            return self::FORCE_UPDATE;
        }

        return $newVersion;
    }

    /**
     * Manueller Update-Check durchführen
     * @return bool
     */
    public function checkManual()
    {
        return (time() > filectime(\fpcm\classes\baseconfig::$versionFile) + $this->config->system_updates_manual) ? true : false;
    }

    /**
     * Gibt Link für Manuelle Update-Prüfung zurück, seit FPCM 3.x Link zur Download-Seite von FanPress CM
     * @return string
     */
    public function getManualCheckAddress()
    {
        return \fpcm\classes\baseconfig::$updateServerManualLink;
    }

}

?>