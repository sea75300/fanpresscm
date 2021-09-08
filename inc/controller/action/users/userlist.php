<?php

/**
 * Login controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

class userlist extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\theme\nav\users;
    
    /**
     *
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     *
     * @var \fpcm\model\users\userRollList
     */
    protected $rollList;

    /**
     *
     * @var \fpcm\model\articles\articlelist
     */
    protected $articleList;

    /**
     *
     * @var array
     */
    protected $chartItems;

    /**
     *
     * @var array
     */
    protected $chartItemColors;

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'users/userlist';
    }

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->users;
    }

    /**
     * 
     * @return bool
     */
    protected function initActionObjects()
    {
        $this->userList     = new \fpcm\model\users\userList();
        $this->rollList     = new \fpcm\model\users\userRollList();
        $this->articleList  = new \fpcm\model\articles\articlelist();
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function request()
    {
        if ($this->request->fromGET('added') == 1) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ADDUSER');
        } elseif ($this->request->fromGET('added') == 2) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ADDROLL');
        }

        if ($this->request->fromGET('edited') == 1) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_EDITUSER');
        } elseif ($this->request->fromGET('edited') == 2) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_EDITROLL');
        }

        return true;
    }

    /**
     * 
     * @return bool
     */
    public function process()
    {
        $this->initTabs();
        
        $this->view->assign('usersListSelect', $this->userList->getUsersNameList());
        
        $chart = new \fpcm\components\charts\chart('pie', 'userArticles');
        $chart->addOptions('legend', [
            'position' => 'bottom'
        ]);
        $this->view->addCssFiles($chart->getCssFiles());
        
        $this->view->addJsFiles(array_merge(['users/module.js'], $chart->getJsFiles()));
        $this->view->addJsLangVars(['USERS_ARTICLES_SELECT', 'HL_OPTIONS_PERMISSIONS']);

        $this->view->setFormAction('users/list');

        $buttons = [
            (new \fpcm\view\helper\linkButton('addUser'))->setUrl(\fpcm\classes\tools::getFullControllerLink('users/add'))->setText('GLOBAL_NEW')->setClass('fpcm-ui-maintoolbarbuttons-tab1')->setIcon('user-plus'),
            (new \fpcm\view\helper\button('userStats'))
                ->setText('USERS_STATS_ARTICLE')
                ->setIcon('chart-pie')
                ->setClass('fpcm-ui-maintoolbarbuttons-tab1')
                ->setData([
                    'bs-toggle' => 'offcanvas',
                    'bs-target' => '#offcanvasUserStats'
                ])
                ->setAria([
                    'bs-controls' => 'offcanvasUserStats',
                ])
        ];
        
        if ($this->permissions->system->rolls) {
            $buttons[] = (new \fpcm\view\helper\linkButton('addRoll'))->setUrl(\fpcm\classes\tools::getFullControllerLink('users/addroll'))->setText('GLOBAL_NEW')->setClass('fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-hidden')->setIcon('user-tag');
        }
        

        $this->view->addButtons($buttons);
        $this->createUsersView();
        
        if ($this->permissions->system->rolls) {
            $this->createRollsView();
        }

        $chart->setLabels(array_keys($this->chartItems));
        
        $chartItem = new \fpcm\components\charts\chartItem(
            array_values($this->chartItems),
            array_values($this->chartItemColors)
        );

        $chartItem->setBorderColor('none');
        
        $chart->setValues($chartItem);
        $this->view->assign('userArticles', $chart);

        $this->view->addJsVars([
            'chartData' => $chart
        ]);
        
        $this->view->includeForms('users');
        $this->view->addAjaxPageToken('users/actions');
        $this->view->render();
        return true;
    }

    /**
     * Benutzer-Dataview erzeugen
     * @return bool
     */
    private function createUsersView()
    {
        $usersInGroups = $this->userList->getUsersAll(true);
        $userGroups    = $this->rollList->getUserRollsByIds(array_keys($usersInGroups));
        
        $notFoundRoll = new \fpcm\model\users\userRoll();
        $notFoundRoll->setRollName($this->language->translate('GLOBAL_NOTFOUND'));
        $notFoundRoll->setId(-1);
        
        $userGroups[-1] = $notFoundRoll;
        if (!isset($usersInGroups[-1])) {
            $usersInGroups[-1] = [];
        }

        array_map(function($diff) use (&$usersInGroups) {

            $usersInGroups[-1] += $diff;

        }, array_diff_key($usersInGroups, $userGroups));
        

        $dataView = new \fpcm\components\dataView\dataView('userlist');
        
        $dataView->addColumns([
            (new \fpcm\components\dataView\column('button', '', 'flex-grow-1'))->setSize('auto')->setAlign('center'),
            (new \fpcm\components\dataView\column('username', 'GLOBAL_USERNAME'))->setSize(3),
            (new \fpcm\components\dataView\column('email', 'GLOBAL_EMAIL'))->setSize(3),
            (new \fpcm\components\dataView\column('registered', 'USERS_REGISTEREDTIME'))->setSize(2)->setAlign('center'),
            (new \fpcm\components\dataView\column('metadata', '', 'flex-grow-1'))->setSize('auto')->setAlign('center'),
        ]);

        $articleCount = $this->articleList->countArticlesByUsers();
        $currentUser = $this->session->getUserId();

        $descr = $this->language->translate('USERS_ROLL');
        
        $usersInGroups = array_filter($usersInGroups, function ($users, $rollId) use ($userGroups) {
            return !isset($userGroups[$rollId]) || ($rollId === -1) && !count($users) ? false : true;
        }, ARRAY_FILTER_USE_BOTH);
        
        
        foreach($usersInGroups AS $rollId => $users) {
            
            $title  = '<b>' . $descr.': '.$this->language->translate($userGroups[$rollId]->getRollName()) . '</b>';
            
            $dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('button', '', 'd-none d-lg-block'),
                    new \fpcm\components\dataView\rowCol('username', $title),
                    new \fpcm\components\dataView\rowCol('email', '', 'd-none d-lg-block'),
                    new \fpcm\components\dataView\rowCol('registered', '', 'd-none d-lg-block'),
                    new \fpcm\components\dataView\rowCol('metadata', '', 'd-none d-lg-block'),
                ], '', true
            ));
            
            /* @var $user \fpcm\model\users\author */
            foreach ($users as $userId => $user) {

                $noRb   = $user->getId() == $currentUser ? true : false;

                $count = isset($articleCount[$userId]) ? $articleCount[$userId] : 0;

                $this->chartItems[$user->getDisplayname()] = $count;
                $this->chartItemColors[$user->getDisplayname()] = \fpcm\components\charts\chartItem::getRandomColor();

                $metadata = [
                    (new \fpcm\view\helper\badge('art'.$userId))->setValue($count)->setText('USERS_ARTICLE_COUNT')->setIcon('book'),
                    (new \fpcm\view\helper\icon('user-slash fa-inverse'))->setText('USERS_DISABLED')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-' . $user->getDisabled())->setStack('square')
                ];
                
                $buttons = [
                    (new \fpcm\view\helper\editButton('useredit'.$userId))->setUrlbyObject($user),
                    (new \fpcm\view\helper\linkButton('usermail'.$userId))->setUrl('mailto:'.$user->getEmail())->setIcon('envelope')->setIconOnly(true)->setText('GLOBAL_WRITEMAIL'),
                ];
                
                if ($user->getDisabled()) {
                    $buttons[] = (new \fpcm\view\helper\submitButton(uniqid('enableUser')))
                        ->setText('GLOBAL_ENABLE')
                        ->setClass('fpcm ui-userlist-actione')
                        ->setIcon('user-check')
                        ->setIconOnly(true)
                        ->setReadonly($noRb)
                        ->setData(['oid' => $userId, 'fn' => 'enableUser', 'dest' => 'confirmExec']);
                }
                else {
                    $buttons[] = (new \fpcm\view\helper\submitButton(uniqid('disableUser')))
                        ->setText('GLOBAL_DISABLE')
                        ->setClass('fpcm ui-userlist-actione')
                        ->setIcon('user-lock')
                        ->setIconOnly(true)
                        ->setReadonly($noRb)
                        ->setData(['oid' => $userId, 'fn' => 'disableUser', 'dest' => 'confirmExec']);
                }
                
                $buttons[] = (new \fpcm\view\helper\deleteButton(uniqid('deleteUser')))
                        ->setClass('fpcm ui-userlist-actione')
                        ->setIconOnly(true)
                        ->setReadonly($noRb)
                        ->setData(['oid' => $userId, 'fn' => 'deleteUser', 'dest' => 'moveDeleteArticles']);

                $dataView->addRow(
                    new \fpcm\components\dataView\row([
                        new \fpcm\components\dataView\rowCol('button', implode('', $buttons), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                        new \fpcm\components\dataView\rowCol('username', new \fpcm\view\helper\escape($user->getDisplayname()) ),
                        new \fpcm\components\dataView\rowCol('email', new \fpcm\view\helper\escape($user->getEmail())),
                        new \fpcm\components\dataView\rowCol('registered', new \fpcm\view\helper\dateText($user->getRegistertime())),
                        new \fpcm\components\dataView\rowCol('metadata', implode('', $metadata), 'fs-5', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                    ]
                ));

            }
            
        }

        $this->view->addDataView($dataView);
        return true;
    }

    /**
     * Benutzer-Dataview erzeugen
     * @return bool
     */
    private function createRollsView()
    {
        $rolls = $this->rollList->getUserRollsTranslated();
        
        $dataView = new \fpcm\components\dataView\dataView('rollslist');
        
        $dataView->addColumns([
            (new \fpcm\components\dataView\column('button', ''))->setSize(2)->setAlign('center'),
            (new \fpcm\components\dataView\column('title', 'USERS_ROLLS_NAME'))->setSize('auto'),
        ]);

        foreach($rolls AS $descr => $rollId) {

            $buttons = [
                (new \fpcm\view\helper\editButton('rollEditBtn'.$rollId))->setUrl(\fpcm\classes\tools::getFullControllerLink('users/editroll', [
                    'id' => $rollId
                ]))
            ];
            
            if ($this->permissions->system->permissions) {
                $buttons[] = (new \fpcm\view\helper\linkButton('rollPermBtn'.$rollId))->setUrl(\fpcm\classes\tools::getFullControllerLink('users/permissions', [
                    'id' => $rollId
                ]))->setIcon('key')
                    ->setIconOnly(true)
                    ->setText('USERS_ROLLS_PERMISSIONS')
                    ->setClass('fpcm ui-rolls-edit')
                    ->setData(['type' => 'iframe']);
            }
            
            $buttons[] = (new \fpcm\view\helper\deleteButton(uniqid('deleteROll')))
                    ->setClass('fpcm ui-rollslist-action-delete')
                    ->setIconOnly(true)
                    ->setReadonly($rollId <= 3)
                    ->setData(['oid' => $rollId, 'fn' => 'deleteRoll']);
            
            $dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('button', implode('', $buttons), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                    new \fpcm\components\dataView\rowCol('title', new \fpcm\view\helper\escape($descr) ),
                ]
            ));

        }

        $this->view->addDataView($dataView);
        return true;
    }
    
    protected function initTabs()
    {
        $tabs = [];
        $tabs[] = (new \fpcm\view\helper\tabItem('users'))
                ->setText('USERS_LIST')
                ->setFile($this->getViewPath())
                ->setTabToolbar(1);
        
        if ($this->permissions->system->rolls) {
            $tabs[] = (new \fpcm\view\helper\tabItem('rolls'))
                    ->setText('USERS_LIST_ROLLS')
                    ->setFile('users/rollslist')
                    ->setTabToolbar(2);
        }
        
        $this->view->addTabs('users', $tabs, 'ui-tabs-function-autoinit', 0);
    }

}

?>
