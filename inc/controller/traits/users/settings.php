<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\users;

/**
 * Author settings assignment trait
 * 
 * @package fpcm\controller\traits\users\authorImages
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1.0-a1
 */
trait settings {

    protected function settingsToView()
    {
        $userRolls = new \fpcm\model\users\userRollList();
        $this->view->assign('userRolls', $userRolls->getUserRollsTranslated());
        $this->view->assign('languages', array_flip($this->language->getLanguages()));        
        
        
        $this->view->assign('timezoneAreas', $this->getTimeZonesAreas());
        $this->view->assign('articleLimitList', \fpcm\model\system\config::getAcpArticleLimits());
        $this->view->assign('defaultFontsizes', \fpcm\model\system\config::getDefaultFontsizes());
        $this->view->assign('filemanagerViews', \fpcm\components\components::getFilemanagerViews());
        $this->view->assign('backdrops', \fpcm\components\components::getBackdropImages());
    }

}
