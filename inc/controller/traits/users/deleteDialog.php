<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\users;

/**
 * User delete dialog trait
 *
 * @package fpcm\controller\traits\users\authorImages
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.4-b1
 */
trait deleteDialog {

    protected function initDeleteConfirmDialog(\fpcm\model\users\userList $list)
    {
        $this->view->addJsLangVars(['USERS_ARTICLES_SELECT', 'USERS_ARTICLES_USER']);

        $this->view->addJsVars([
            'deleteForm' => [
                'articles-action' =>                 [
                    'call' => 'select',
                    'class' => '',
                    'options' => $this->language->translate('USERS_ARTICLES_LIST'),
                    'label' => 'USERS_ARTICLES_SELECT',
                ],
                'articles-user' =>                 [
                    'call' => 'select',
                    'class' => '',
                    'options' => $list->getUsersNameList(),
                    'label' => 'USERS_ARTICLES_USER',
                ]
            ]
        ]);

    }

}
