<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\files;

/**
 * Dateimanager-Liste
 * 
 * @package fpcm\controller\traits\files\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait lists {

    /**
     *
     * @var array
     */
    protected $permissionsData = [];

    /**
     *
     * @var \fpcm\view\message
     */
    protected $filterError = null;

    /**
     * Berechtigungen initialisieren
     */
    public function initPermissions()
    {
        $this->permissionsData['permUpload'] = $this->permissions->uploads->add;
        $this->permissionsData['permDelete'] = $this->permissions->uploads->delete;
        $this->permissionsData['permThumbs'] = $this->permissions->uploads->thumbs;
        $this->permissionsData['permRename'] = $this->permissions->uploads->rename;

        foreach ($this->permissionsData as $key => $value) {
            $this->view->assign($key, $value);
        }
    }

    /**
     * View-Variablen initialisieren
     * @param array $list
     * @param array $users
     * @param array $pagerData
     */
    public function initViewAssigns($list, $users, $pagerData)
    {
        $this->view->assign('files', $list);
        $this->view->assign('users', $users);
        $this->view->assign('mode', $this->mode);
        $this->view->assign('filterError', $this->filterError);
        $this->view->assign('listAction', 'files/list');

        $this->view->assign('showPager', true);
        foreach ($pagerData as $key => $value) {
            $this->view->assign($key, $value);
        }

    }

}
