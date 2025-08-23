<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * User settings dialog btrait
 *
 * @package fpcm\controller\traits\comments\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait listSettings {

    final public function addListSettingsDialog()
    {
        $this->view->addToolbarRight((new \fpcm\view\helper\button('settings'))->setText('HL_OPTIONS')->setIcon('cogs')->setIconOnly());

        $settingsDlg = (new \fpcm\view\helper\dialog('settings'));

        $settingsDlg->setFields([
            (new \fpcm\view\helper\select('articles_acp_limit'))
                ->setText('SYSTEM_OPTIONS_ACPARTICLES_LIMIT')
                ->setOptions( \fpcm\model\system\config::getAcpArticleLimits() )
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setSelected($this->config->articles_acp_limit)
                ->setData([
                    'user_setting' => 'articles_acp_limit',
                    'index' => 0
                ])
                ->setIcon('list')
                ->setLabelTypeFloat()
                ->setBottomSpace('')
        ]);

        $this->view->addDialogs($settingsDlg);
        $this->view->addJsLangVars(['HL_OPTIONS']);
    }
}
