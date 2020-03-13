<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * AJAX testing controller
 * 
 * @package fpcm\controller\ajax\system\refresh
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @ignore
 */
class testing extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    public function isAccessible(): bool
    {
        return true;
    }

    /**
     * @see \fpcm\controller\abstracts\controller::hasAccess()
     * @return bool
     */
    public function hasAccess()
    {
        return defined('FPCM_DEBUG') && FPCM_DEBUG;
    }
    
    /**
     * Controller-Processing
     */
    public function process()
    {
        $ts = $this->request->fromPOST('timestamp', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if (!$ts) {
            $this->getSimpleResponse();
        }

        $step = $this->request->fromPOST('step', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $fopt = new \fpcm\model\files\fileOption('lastchange');

        $step++;


        
        $this->returnData = [
            'step' => $step,
            'res' => mt_rand(0,1),
            'data' => []
        ];

        while ($fopt->read() <= $ts) {


            sleep(2);
            clearstatcache();
            $ts = $fopt->read();

            $this->returnData['res'] = $step >= 5 ? 2 : mt_rand(0,1);
            $this->returnData['data'][] =  (string) new \fpcm\view\helper\dateText(time(), 'd.m.Y H:i:s');


            if ($this->returnData['res']) {
                break;
            }

        }

        (new \fpcm\model\http\response())->setReturnData($this->returnData)->fetch();

    }


}

?>