<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Backup manager controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class twitterAssistant
extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\viewByNamespace
{
    use \fpcm\controller\traits\articles\newteets,
        \fpcm\controller\traits\common\fetchHelp;

    /**
     * 
     * @var \fpcm\model\system\twitter
     */
    private $obj;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->obj = $this->getTwitterInstace();
    }
    
    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        if (!function_exists('curl_init') && \fpcm\classes\baseconfig::canConnect()) {
            return false;
        }
        
        if (!$this->permissions->system->options) {
            return false;
        }
        
        if ($this->obj->checkRequirements()) {
            return true;
        }
        
        return false;        
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $xml = $this->getChapter('SYSTEM_HL_OPTIONS_TWITTER');
        $descr = $xml[0];

        $this->view->assign('xml', $descr);
        
        $this->view->addTabs('twitterassistant', [
            (new \fpcm\view\helper\tabItem('twitter-steps'))->setText('SYSTEM_OPTIONS_TWITTER_CONNECT')->setFile($this->getViewPath())            
        ]);
        
    }

}
