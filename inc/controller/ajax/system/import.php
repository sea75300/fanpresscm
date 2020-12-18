<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * AJAX import controller
 * 
 * @package fpcm\controller\ajax\system\refresh
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class import extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     * 
     * @var \fpcm\model\abstracts\dataset
     */
    private $instance;

    public function isAccessible(): bool
    {
        return $this->permissions->system->options;
    }
    
    /**
     * Controller-Processing
     */
    public function process()
    {

        $current = $this->request->fromPOST('current', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $next = (bool) $this->request->fromPOST('next', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $fpath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_OPTIONS, 'import.csv');
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

    /**
     * 
     * @return bool
     */
    private function initImportItem() : bool
    {
        if ($this->instance instanceof \fpcm\model\interfaces\isCsvImportable) {
            return true;
        }

        $item = $this->request->fromPOST('item');

        $class = 'fpcm\\model\\'.$item;
        if (!is_a($class, '\fpcm\model\interfaces\isCsvImportable')) {
            $this->response->setCode(415)->setReturnData(new \fpcm\view\message('Ungültiger Typ', \fpcm\view\message::TYPE_ERROR ))->fetch();
        }
        
        $this->instance = new $class;
        return true;
    }


}

?>