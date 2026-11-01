<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\navigation;

/**
 * Module-Event: add
 *
 * Event wird ausgeführt, wenn Navigation erzeugt wird
 * Parameter: array mit Standard-Navigationspunkten
 * Rückgabe: array mit Navigationsstruktur
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class add extends \fpcm\events\abstracts\event {

    /**
     * Event-Daten
     * @var \fpcm\model\theme\navigationList
     */
    protected $data;

    /**
     * Prepare event before running
     * @return bool
     * @since 5.3.0-dev
     */
    protected function beforeProcess(\fpcm\module\event $module) : void
    {
        $this->data->setExtModuleKey($module->getModuleKey());
    }

    /**
     * After event running
     * @return bool
     * @since 5.3.0-dev
     */
    protected function afterProcess() : void
    {
        parent::afterProcess();
        $this->data->setExtModuleKey('');
    }

}
