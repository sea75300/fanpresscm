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
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    trait lists {

        /**
         * Berechtigungen initialisieren
         */
        public function initPermissions() {
            $this->view->assign('permUpload', $this->permissions->check(array('uploads' => 'add')));
            $this->view->assign('permDelete', $this->permissions->check(array('uploads' => 'delete')));
            $this->view->assign('permThumbs', $this->permissions->check(array('uploads' => 'thumbs')));
            $this->view->assign('permRename', $this->permissions->check(array('uploads' => 'rename')));;               
        }
        
        /**
         * View-Variablen initialisieren
         * @param array $list
         * @param array $users
         * @param array $pagerData
         */
        public function initViewAssigns($list, $users, $pagerData) {
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