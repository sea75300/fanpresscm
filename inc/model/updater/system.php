<?php

/**
 * FanPress CM 5.x
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
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\updater
 * 
 * @property string $version New system version
 * @property bool $force Force update to new version
 * @property string $url URL for package
 * @property string $signature Package signature
 * @property string $phpversion Minimum required PHP version
 * @property string $release Package release
 * @property string $changelog Changelog URL
 * @property int $size Package size
 */
final class system
extends staticModel
implements \fpcm\model\interfaces\isObjectInstancable
{
    
    use \fpcm\model\traits\getObjectInstance;

    /**
     * Status, dass Update erzwungen wird
     */
    const FORCE_UPDATE = 1001;

    /**
     * Develop prefix
     * @since 4.4.3-rc1
     */
    const PREFIX_DEV = 'dev';

    /**
     * Default prefix
     * @since 4.4.3-rc1
     */
    const PREFIX_DEFAULT = 'default';

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

        $newVersion = version_compare($this->version, $this->config->system_version, '>');
        if ($newVersion && isset($this->phpversion) && version_compare(phpversion(), $this->phpversion, '<')) {
            fpcmLogSystem('FanPress CM ' . $this->version . ' is available, but requires PHP ' . $this->phpversion . ' or higher.');
            return true;
        }

        if ( $newVersion && $this->force ) {
            return self::FORCE_UPDATE;
        }

        return $newVersion;
    }

    /**
     * Initialize class data
     * @return bool
     */
    public function init()
    {
        $this->fileOption = new fileOption(repository::FOPT_UPDATES);

        $foptData = \Spyc::YAMLLoadString($this->fileOption->read());
        
        $currentVersionComplete = $this->config->system_version;
        $currentVersionMinor    = \fpcm\classes\tools::getMajorMinorReleaseFromString($currentVersionComplete);

        if ($this->config->system_updates_devcheck) {
            $currentVersionComplete .= '-'.self::PREFIX_DEV;
            $currentVersionMinor .= '-'.self::PREFIX_DEV;
        }

        if (isset($foptData[$currentVersionComplete])) {
            $this->data = $foptData[$currentVersionComplete];
        }
        elseif (isset($foptData[$currentVersionMinor]) ) {
            $this->data = $foptData[$currentVersionMinor];
        }
        elseif ($this->config->system_updates_devcheck && isset ($foptData[self::PREFIX_DEV])) {
            $this->data = $foptData[self::PREFIX_DEV];
        }
        else {
            $this->data = $foptData[self::PREFIX_DEFAULT] ?? [];
        }

        if ($this->size === null) {
            $this->size = 0;
        }

        if ($this->changelog === null) {
            $this->changelog = '';
        }

        return true;
    }

    /**
     * Check if data/config/files.txt path exists
     * @return bool
     * @since 4.1
     */
    final public function filesListExists() : bool
    {
        return file_exists(update::getFilesListPath());
    }

}
