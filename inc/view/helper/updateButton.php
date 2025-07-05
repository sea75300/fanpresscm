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
final class copyButton extends button {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->setText('PACKAGES_UPDATE');
        $this->setIcon('sync');

        $update = new \fpcm\model\updater\system();
        
        
        $this->setData([
            'changelog' => $this->systemUpdates->changelog ?? '',
            'update' => \fpcm\classes\tools::getFullControllerLink('package/sysupdate')
        ]);

    }


}
