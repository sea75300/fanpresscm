<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\files;

/**
 * Dateimanager-Liste
 * 
 * @package fpcm\controller\traits\files\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait lists {

    /**
     *
     * @var \fpcm\view\message
     */
    protected $filterError = null;

    /**
     * View-Variablen initialisieren
     * @param array $list
     * @param array $users
     * @param array $pagerData
     */
    public function initViewAssigns($list, $users)
    {
        $this->view->assign('files', $list);
        $this->view->assign('users', $users);
        $this->view->assign('mode', $this->mode);
        $this->view->assign('filterError', $this->filterError);
        $this->view->assign('listAction', 'files/list');
    }

}
