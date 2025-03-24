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

        $dialog = new \fpcm\view\helper\dialog('deleteForm');
        $dialog->setFields([
            (new \fpcm\view\helper\select('articles-action'))
                ->setOptions( $this->language->translate('USERS_ARTICLES_LIST') )
                ->setText('USERS_ARTICLES_SELECT')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            (new \fpcm\view\helper\select('articles-user'))
                ->setOptions( $list->getUsersNameList() )
                ->setText('USERS_ARTICLES_USER')
                ->setLabelTypeFloat()
                ->setBottomSpace('')
        ]);

        $this->view->addDialogs($dialog);

    }

}
