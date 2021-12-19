<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * Module migration base class
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 4.5.1-b1
 */
abstract class migration extends \fpcm\migrations\migration {

    use tools;

    /**
     * Migration execution required due to system version
     * @return bool
     */
    final public function compareVersion() : bool
    {
        return $this->getObject()->hasLocalUpdates();
    }

    /**
     * return preview version string
     * @return string
     */
    final protected function getPreviewsVersion() : string
    {
        return $this->getObject()->getConfig()->version;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return (new config($this->getModuleKey(), null))->version;
    }

    /**
     * Returns migration class namespace
     * @param string $version
     * @return string
     * @static
     */
    public static function getNamespace(string $version) : string
    {
        module::getMigrationNamespace( $this->getModuleKey() , $version);
    }

}
