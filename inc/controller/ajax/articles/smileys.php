<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * Editor smiley ajax controller
 * 
 * @package fpcm\controller\ajax\articles\smileys
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class smileys extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->editArticles() || $this->permissions->article->add;
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return $this->request->fetchAll('json') ? '' : 'articles/editors/smileys';
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $values = array_values((new \fpcm\model\files\smileylist())->getDatabaseList());

        if ($this->request->fetchAll('json')) {
            (new \fpcm\model\http\response)->setReturnData($values)->fetch();
        }

        $this->view->assign('smileys', $values);
    }

}

?>