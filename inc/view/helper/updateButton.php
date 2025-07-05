<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Edit link button view helper object
 *
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
final class updateButton extends button {

    /**
     * Updater object
     * @var \fpcm\model\updater\system
     */
    protected $updater;

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->setText('PACKAGES_UPDATE');
        $this->setIcon('sync');

        $this->setData([
            'changelog' => $this->getUpdater()->changelog ?? '',
            'update' => \fpcm\classes\tools::getFullControllerLink('package/sysupdate')
        ]);

    }

    /**
     * Get Updater object
     * @return \fpcm\model\updater\system
     */
    public function getUpdater(): \fpcm\model\updater\system
    {
        if (!$this->updater instanceof \fpcm\model\updater\system) {
            $this->updater = \fpcm\model\updater\system::getInstance();
        }

        return $this->updater;
    }

    /**
     * Set updater object
     * @param \fpcm\model\updater\system $updater
     * @return $this
     */
    public function setUpdater(\fpcm\model\updater\system $updater)
    {
        $this->updater = $updater;
        return $this;
    }


}
