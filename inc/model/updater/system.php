<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\updater;

use fpcm\classes\baseconfig;
use fpcm\classes\loader;
use fpcm\model\abstracts\remoteModel;
use fpcm\model\abstracts\staticModel;
use fpcm\model\files\fileOption;
use fpcm\model\packages\repository;
use fpcm\model\packages\update;

/**
 * System updater object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * 
 * @property string $version New system version
 * @property bool $force Force update to new version
 * @property string $url URL for package
 * @property string $signature Package signature
 * @property string $phpversion Minimum required PHP version
 * @property string $release Package release
 * @property int $size Package size
 */
final class system extends staticModel {

    /**
     * Status, dass Update erzwungen wird
     */
    const FORCE_UPDATE = 1001;

    /**
     * File option object for repo data
     * @var \fpcm\model\files\fileOption
     */
    private $fileOption;

    /**
     * Prüft ob Updates verfügbar sind
     * @return bool
     */
    public function updateAvailable()
    {
        if (count($this->data) < 2) {
            return remoteModel::FURLOPEN_ERROR;
        }

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
        if ($this->updateAvailable() !== remoteModel::FURLOPEN_ERROR) {
            return false;
        }

        return (!baseconfig::canConnect() && time() > filectime(baseconfig::getVersionFromFile()) + $this->config->system_updates_manual) ? true : false;
    }

    /**
     * Gibt Link für Manuelle Update-Prüfung zurück, seit FPCM 3.x Link zur Download-Seite von FanPress CM
     * @return string
     */
    public function getManualCheckAddress()
    {
        return baseconfig::$updateServerManualLink;
    }

    /**
     * Initialize class data
     * @return bool
     */
    public function init()
    {
        $this->fileOption = new fileOption(repository::FOPT_UPDATES);

        include_once loader::libGetFilePath('spyc/Spyc.php');
        $foptData = \Spyc::YAMLLoadString($this->fileOption->read());
        
        $currentVersionComplete = $this->config->system_version;
        $currentVersionMinor    = implode('.', array_slice(explode('.', $currentVersionComplete), 0, 2));

        if ($this->config->system_updates_devcheck) {
            $currentVersionComplete .= '-dev';
            $currentVersionMinor .= '-dev';
        }

        if (isset($foptData[$currentVersionComplete])) {
            $this->data = $foptData[$currentVersionComplete];
            if ($this->size === null) {
                $this->size = 0;
            }
            return true;
        }

        if (isset($foptData[$currentVersionMinor]) ) {
            $this->data = $foptData[$currentVersionMinor];
            if ($this->size === null) {
                $this->size = 0;
            }
            return true;
        }

        $this->data = isset($foptData['default']) ? $foptData['default'] : [];
        if ($this->size === null) {
            $this->size = 0;
        }

        return true;
    }

    /**
     * Check if data/config/files.txt path exists
     * @return bool
     * @since FPCM 4.1
     */
    final public function filesListExists() : bool
    {
        return file_exists(update::getFilesListPath());
    }

}

?>