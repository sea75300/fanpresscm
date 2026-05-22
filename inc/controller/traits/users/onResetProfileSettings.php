<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\users;

/**
 * Author image processing trait
 * 
 * @package fpcm\controller\traits\users\authorImages
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait onResetProfileSettings {

    /**
     * Reload pae
     * @var bool
     */
    protected $reloadSite = false;

    /**
     * Reset profile settings
     * @return bool
     */
    protected function onResetProfileSettings() : bool
    {
        if (!$this->checkPageToken) {
            return false;
        }

        if ($this->user->resetProfileSettings() === false) {
            $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
            return false;
        }

        $this->view->addNoticeMessage('SAVE_SUCCESS_RESETPROFILE');
        $this->reloadSite = true;
        return true;
    }
}
