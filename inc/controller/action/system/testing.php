<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Testing controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class testing extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\isAccessible,
           \fpcm\controller\interfaces\viewByNamespace {

    public function isAccessible(): bool
    {
        return defined('FPCM_DEBUG') && FPCM_DEBUG;
    }

    /**
     * 
     * @return bool
     */
    public function process() : bool
    {
        $this->view->addJsFiles([
            'testing.js'
        ]);
        
        $this->view->assign('progressbarName', 'testing');

        return true;
    }

}

?>