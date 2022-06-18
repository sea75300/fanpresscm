<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * Remove tags from article
 * 
 * @package fpcm\controller\ajax\articles\removeeditortags
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class removeeditortags extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->editArticles() || $this->permissions->article->add;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        exit(strip_tags($this->request->fromPOST('text')));
    }

}

?>