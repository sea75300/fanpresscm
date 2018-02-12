<?php

/**
 * FanPress CM 3.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\files;

/**
 * Dateimanager-Liste
 * 
 * @package fpcm\controller\traits\files\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait lists {

    protected $permissionsData = [];

    /**
     * Berechtigungen initialisieren
     */
    public function initPermissions()
    {
        $this->permissionsData['permUpload'] = $this->permissions->check(['uploads' => 'add']);
        $this->permissionsData['permDelete'] = $this->permissions->check(['uploads' => 'delete']);
        $this->permissionsData['permThumbs'] = $this->permissions->check(['uploads' => 'thumbs']);
        $this->permissionsData['permRename'] = $this->permissions->check(['uploads' => 'rename']);

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

        $this->view->assign('showPager', true);
        foreach ($pagerData as $key => $value) {
            $this->view->assign($key, $value);
        }

        $this->view->assign('listAction', 'files/list');
    }

}
