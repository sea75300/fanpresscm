<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\comments\lists;

/**
 * Comment list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class all extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\common\lists,
        \fpcm\controller\traits\common\massedit,
        \fpcm\controller\traits\common\listSettings,
        \fpcm\controller\traits\comments\lists,
        \fpcm\controller\traits\comments\massEdit;

    /**
     *
     * @var int
     */
    protected $mode = 1;

    /**
     *
     * @return string
     */
    protected function getHelpLink()
    {
        return 'hl_comments_mng';
    }

    /**
     *
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'comments/commentlist';
    }

    /**
     * Controller processing
     * @return void
     */
    public function process()
    {
        $this->view->addAjaxPageToken('comments/delete');
        $this->view->assign('commentsMode', 1);
        $this->view->addJsLangVars([
            'DELETE_SUCCESS_COMMENTS', 'DELETE_FAILED_COMMENTS', 'COMMMENT_AUTHOR',
            'GLOBAL_EMAIL', 'COMMMENT_WEBSITE', 'ARTICLE_SEARCH_TEXT',
            'ARTICLE_SEARCH_DATE_FROM', 'ARTICLE_SEARCH_DATE_TO', 'COMMMENT_SPAM',
            'COMMMENT_PRIVATE', 'COMMMENT_APPROVE', 'COMMMENT_IPADDRESS',
            'COMMMENT_SEARCH_ARTICLE', 'COMMMENT_CREATEDATE'
        ]);

        $this->initSearchForm();
        $this->initCommentMassEditForm(1);

        $this->view->addJsFiles(['comments/module.js', 'comments/search.js', 'comments/deleteCallback.js', 'ui/dnd.js']);
        $this->view->setFormAction('comments/list');

        $searchPrimary = true;
        if ($this->permissions->editCommentsMass()) {
            $searchPrimary = false;
            $this->view->addButton((new \fpcm\view\helper\button('massEdit'))->setText('GLOBAL_EDIT')->setIcon('edit')->setPrimary());
        }

        $this->view->addButton((new \fpcm\view\helper\button('opensearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly()->setPrimary($searchPrimary));

        if ($this->permissions->comment->delete) {
            $this->view->addButton( (new \fpcm\view\helper\button('deleteComment'))
                ->setText('GLOBAL_DELETE')
                ->setIcon('trash')
                ->setIconOnly()
                ->setOnClick('comments.deleteMultipleArticle')
            );
        }

        $this->addListSettingsDialog();

        $this->view->addDataView( new \fpcm\components\dataView\dataView('commentlist') );
        $this->view->addPager(new \fpcm\view\helper\pager('comments/lists', $this->page, 1, $this->config->articles_acp_limit, 1));

        $this->view->addTabs('comments', [
            (new \fpcm\view\helper\tabItem('tabs-comments-list'))
                ->setText('COMMMENT_HEADLINE')
                ->setFile($this->getViewPath() . '.php')
        ]);

    }

    /**
     * Initialisiert Suchformular-Daten
     * @param array $users
     */
    private function initSearchForm()
    {
        $searchDlg = new \fpcm\view\helper\dialogs\search();
        $searchDlg->setFields([
            'valueFields' => [
                'name' => (new \fpcm\view\helper\textInput('name'))
                    ->setText('COMMMENT_AUTHOR')
                    ->setLabelTypeFloat(),
                'email' => (new \fpcm\view\helper\textInput('email'))
                    ->setText('GLOBAL_EMAIL')
                    ->setLabelTypeFloat(),
                'website' => (new \fpcm\view\helper\textInput('website'))
                    ->setText('COMMMENT_WEBSITE')
                    ->setLabelTypeFloat(),
                'text' => (new \fpcm\view\helper\textInput('text'))
                    ->setText('ARTICLE_SEARCH_TEXT')
                    ->setLabelTypeFloat(),
                'datefrom' => (new \fpcm\view\helper\dateTimeInput('datefrom'))
                    ->setText('ARTICLE_SEARCH_DATE_FROM')
                    ->setNativeDate()
                    ->setLabelTypeFloat(),
                'dateto' => (new \fpcm\view\helper\dateTimeInput('dateto'))
                    ->setText('ARTICLE_SEARCH_DATE_FROM')
                    ->setNativeDate()
                    ->setLabelTypeFloat(),
                'spam' => (new \fpcm\view\helper\boolSelect('spam'))
                    ->setText('COMMMENT_SPAM')
                    ->setSelected(-1)
                    ->setExtendedList()
                    ->setLabelTypeFloat(),
                'private' => (new \fpcm\view\helper\boolSelect('private'))
                    ->setText('COMMMENT_PRIVATE')
                    ->setSelected(-1)
                    ->setExtendedList()
                    ->setLabelTypeFloat(),
                'approved' => (new \fpcm\view\helper\boolSelect('approved'))
                    ->setText('COMMMENT_APPROVE')
                    ->setSelected(-1)
                    ->setExtendedList()
                    ->setLabelTypeFloat(),
                'ipaddress' => (new \fpcm\view\helper\textInput('ipaddress'))
                    ->setText('COMMMENT_IPADDRESS')
                    ->setLabelTypeFloat(),
                'articleid' => (new \fpcm\view\helper\textInput('articleid'))
                    ->setText('COMMMENT_SEARCH_ARTICLE')
                    ->setLabelTypeFloat(),
            ],
            'buildFields' => [
                (new \fpcm\view\helper\button('cremove'))
                    ->setText('GLOBAL_REMOVE')
                    ->setIcon('minus')
                    ->setIconOnly()
                    ->setClass('btn-sm')
                    ->setLabelTypeFloat(),
                (new \fpcm\view\helper\select('combinations'))
                    ->setText('ARTICLE_SEARCH_LOGIC')
                    ->setOptions($searchDlg->getDefaultCombinations())
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected(-1)
                    ->setLabelTypeFloat(),
                (new \fpcm\view\helper\select('fields'))
                    ->setOptions([
                        'COMMMENT_AUTHOR' => 'name',
                        'GLOBAL_EMAIL' => 'email',
                        'COMMMENT_WEBSITE' => 'website',
                        'ARTICLE_SEARCH_TEXT' => 'text',
                        'ARTICLE_SEARCH_DATE_FROM' => 'datefrom',
                        'ARTICLE_SEARCH_DATE_TO' => 'dateto',
                        'COMMMENT_SPAM' => 'spam',
                        'COMMMENT_PRIVATE' => 'private',
                        'COMMMENT_APPROVE' => 'approved',
                        'COMMMENT_IPADDRESS' => 'ipaddress',
                        'COMMMENT_SEARCH_ARTICLE' => 'articleid',
                    ])
                    ->setLabelTypeFloat()
            ],
            'sortFields' => [
                (new \fpcm\view\helper\select('field'))
                    ->setText('GLOBAL_SORT_BY')
                    ->setOptions([
                        'COMMMENT_AUTHOR' => 'name',
                        'GLOBAL_EMAIL' => 'email',
                        'COMMMENT_WEBSITE' => 'website',
                        'ARTICLE_SEARCH_TEXT' => 'text',
                        'COMMMENT_CREATEDATE' => 'createtime',
                        'COMMMENT_SPAM' => 'spammer',
                        'COMMMENT_PRIVATE' => 'private',
                        'COMMMENT_APPROVE' => 'approved',
                        'COMMMENT_IPADDRESS' => 'ipaddress',
                    ])
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected('createtime')
                    ->setLabelTypeFloat(),
                (new \fpcm\view\helper\select('order'))
                    ->setText('GLOBAL_SORT_ODER')
                    ->setOptions($this->language->translate('GLOBAL_SORTBY_LIST'))
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected('desc')
                    ->setLabelTypeFloat(),
            ]
        ]);

        $this->view->addDialogs($searchDlg);

        $this->view->addJsVars([
            'commentsLastSearch' => 0,
            'massEditSaveFailed' => 'SAVE_FAILED_COMMENTS',
            'listMode' => 'all'
        ]);
        
        $this->view->addFromLibrary('sortable_js/', [
            'Sortable.min.js'
        ]);
    }

    protected function getDataViewTabs() : array
    {
        return [
            (new \fpcm\view\helper\tabItem('tabs-commentlist-list'))
                ->setUrl('#tabs-commentlist-list')
                ->setText('COMMMENT_HEADLINE')
                ->setFile($this->getViewPath() . '.php')
        ];
    }

}
