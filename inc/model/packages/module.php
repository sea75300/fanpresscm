<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\packages;

/**
 * System package objekt
 * 
 * @package fpcm\model\packages
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.1
 */
class module extends package {

    /**
     *
     * @var \fpcm\model\updater\modules
     */
    protected $repo;
    /**
     *
     * @var \fpcm\model\updater\modules
     */
    protected $hashKey;

    protected function initObjects()
    {
        $this->repo = (new \fpcm\model\updater\modules())->getDataCachedByKey($this->packageName);
        $this->hashKey = \fpcm\classes\tools::getHash($this->packageName);
        return true;
    }

    protected function getExtractionPath()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, 'module_'.$this->hashKey);
    }

    public function getLocalDestinationPath()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $this->hashKey);
    }

    public function getLocalPath()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, $this->packageName);
    }

    public function getLocalSignature()
    {
        return \fpcm\model\files\ops::hashFile($this->getLocalPath());
    }

    protected function getPackageKey()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_CONFIG, 'package.key');
    }

    public function getRemotePath()
    {
        return $this->repo['packageUrl'];
    }

    public function getRemoteSignature()
    {
        return $this->repo['signature'];
    }

    public function checkFiles()
    {
        return true;
    }

    public function copy()
    {
        return true;
    }

    public function updateLog()
    {
        return true;
    }


}
