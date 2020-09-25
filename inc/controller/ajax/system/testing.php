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
        $current = $this->request->fromPOST('current', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $next = $this->request->fromPOST('next', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $reponseData = [
            'current' => 0,
            'next' => 0,
            'data' => [
                'fs' => 0,
                'lines' => []
            ],
        ];
        
        if (!$next) {
            $this->response->setReturnData($reponseData)->fetch();
        }

        $timer = time();

        $fpath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_OPTIONS, 'import.csv');
        $handle = fopen($fpath, 'r');
        
        if (!is_resource($handle)) {
            $this->response->setReturnData($reponseData)->fetch();
        }

        if (fseek($handle, $current) === -1) {
            $this->response->setReturnData($reponseData)->fetch();
        }

        $reponseData['current']    = $current;
        $reponseData['next']       = !feof($handle);
        $reponseData['data']['fs'] = filesize($fpath);

        $fetch = $reponseData['next'] > 0 ? 1 : 0;
        
        if (!$fetch) {
            $this->response->setReturnData($reponseData)->fetch();
        }
        
        while ($fetch === 1) {
            
            $line = fgetcsv($handle);
            if (is_array($line) && count($line)) {
                $reponseData['data']['lines'][]  = $line;
            }

            $reponseData['current'] = ftell($handle);
            $fetch = feof($handle) ? -1 : ( (time() - $timer) > 5 ? 0 : 1 );
            usleep(5000);
        }

        $reponseData['next'] = feof($handle) ? 0 : 1;

        fclose($handle);
        $this->response->setReturnData($reponseData)->fetch();

    }


}

?>