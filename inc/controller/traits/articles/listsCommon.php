<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\articles;

/**
 * Artikelliste trait
 * 
 * @package fpcm\controller\traits\articles\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait listsCommon {

    /**
     *
     * @var array
     */
    protected $categories = [];

    /**
     *
     * @var array
     */
    protected $users = [];

    /**
     * 
     * @return bool
     */
    protected function initActionObjects()
    {
        $this->articleList = new \fpcm\model\articles\articlelist();
        $this->categoryList = new \fpcm\model\categories\categoryList();
        $this->commentList = new \fpcm\model\comments\commentList();
        $this->userList = new \fpcm\model\users\userList();
        
        $this->users = $this->userList->getUsersNameList();
        $this->categories = $this->categoryList->getCategoriesNameListCurrent();
        return true;
    }

    /**
     * 
     * @return string
     */
    protected function getDataViewName()
    {
        return 'articlelist';
    }

}
