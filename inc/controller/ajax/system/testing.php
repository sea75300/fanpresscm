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
        if (!$this->checkReferer()) {
            return false;
        }

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
        fpcmLogSystem($_REQUEST);
        fpcmLogSystem($_FILES);
        
        
        return true;
        
        $current = $this->request->fromPOST('current', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $next = (bool) $this->request->fromPOST('next', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        $fpath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_OPTIONS, 'import.csv');

        if (!file_exists($fpath)) {
            $this->response->setReturnData([
                'next' => 0,
                'current' => 1,
                'stop' => 1
            ])->fetch();
        }

        $handle = fopen($fpath, 'r');

        $progressObj = new \fpcm\model\system\progress(function (&$data, &$current, $next, &$stop) use (&$handle) {

            if ($current >= $data['fs'] * 0.5) {
                fpcmLogSystem('Stopped reading file after 50% of filesize');
                $stop = true;
                return false;
            }
            
            $line = fgetcsv($handle);
            if (is_array($line) && count($line)) {
                $data['lines'][]  = $line;
            }

            $current = ftell($handle);
            usleep(2000);

            return !feof($handle) ? true : false;
        });

        $progressObj->setNext($next)->setData([
            'fs' => filesize($fpath),
            'lines' => []
        ]);

        if (!is_resource($handle)) {
            $this->response->setReturnData($progressObj)->fetch();
        }

        if (!$progressObj->getNext()) {
            $this->response->setReturnData($progressObj)->fetch();
        }

        if (fseek($handle, $current) === -1) {
            $this->response->setReturnData($progressObj)->fetch();
        }

        $progressObj->setCurrent($current)->setNext(!feof($handle));
        $progressObj->process();
        
        if (!$progressObj->getStop()) {
            $progressObj->setNext(!feof($handle));
        }
        

        fclose($handle);

        $this->response->setReturnData($progressObj)->fetch();

    }


}

?>