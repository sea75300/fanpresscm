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

    private $current;
    
    private $next;
    
    private $file;

    private $delim;

    private $enclosure;

    private $fields;

    public function isAccessible(): bool
    {
        return $this->permissions->system->options;
    }
    
    public function request()
    {
        $this->current = $this->request->fromPOST('current', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $this->next = (bool) $this->request->fromPOST('next', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $csvParams = $this->request->fromPOST('csv');
        
        fpcmLogSystem(__METHOD__);
        fpcmLogSystem($csvParams);
        
        $this->file = filter_var($csvParams['file'] ?? null, FILTER_SANITIZE_STRING);

        $this->delim = filter_var($csvParams['delim'] ?? null, FILTER_SANITIZE_STRING);
        if (!in_array($this->delim, [';', ','])) {
            $this->delim = ';';
        }
        
        $this->enclosure = filter_var($csvParams['enclo'] ?? null, FILTER_SANITIZE_STRING);
        if (!in_array($this->delim, ['"', '\''])) {
            $this->delim = '"';
        }
        
        $this->fields = $csvParams['fields'] ?? [];
        if (!count($this->fields)) {
            $this->response->setReturnData(new \fpcm\view\message(
                'Keine Felder zugewiesen.',
                \fpcm\view\message::TYPE_ERROR,
                \fpcm\view\message::ICON_ERROR,
                '',
                true
            ))->fetch();
        }

        $this->initImportItem();
        return true;
    }


    
    /**
     * Controller-Processing
     */
    public function process()
    {
        $csv = new \fpcm\model\files\csvFile($this->file);        
        
        if ( !$csv->exists() || !$csv->isValidDataFolder('', \fpcm\classes\dirs::DATA_TEMP ) ) {
            $this->response->setReturnData(new \fpcm\view\message(
                'Die CSV-Datei wurde nicht gefunden!',
                \fpcm\view\message::TYPE_ERROR,
                \fpcm\view\message::ICON_ERROR,
                '',
                true
            ))->fetch();
        }

        if ( \fpcm\model\files\csvFile::isValidType($csv->getExtension(), $csv->getMimeType())  ) {
            $this->response->setReturnData(new \fpcm\view\message(
                'Übermittelte Datei ist ungültig!',
                \fpcm\view\message::TYPE_ERROR,
                \fpcm\view\message::ICON_ERROR,
                '',
                true
            ))->fetch();            
        }

        $progressObj = new \fpcm\model\system\progress(function (&$data, &$current, $next, &$stop) use (&$csv) {

            if ($current >= $data['fs'] * 0.5) {
                fpcmLogSystem('Stopped reading file after 50% of filesize');
                $stop = true;
                return false;
            }
            
            $line = $csv->getCsv($this->delim, $this->enclosure);
            if (!$csv->assignCsvFields($this->fields, $line)) {
                
                $this->response->setReturnData(new \fpcm\view\message(
                    'Übermittelte Datei ist ungültig!',
                    \fpcm\view\message::TYPE_ERROR,
                    \fpcm\view\message::ICON_ERROR,
                    '',
                    true
                ))->fetch();
                
            }
            
            fpcmLogSystem('csv import line after assignment in $line');
            fpcmLogSystem($line);

            $current = $csv->tell();
            usleep(2000);

            return !$csv->isEoF() ? true : false;
        });

        $progressObj->setNext($next)->setData([
            'fs' => filesize($this->file),
            'lines' => []
        ]);

        if (!$csv->hasResource()) {
            $this->response->setReturnData($progressObj)->fetch();
        }

        if (!$progressObj->getNext()) {
            $this->response->setReturnData($progressObj)->fetch();
        }

        if ($csv->seek($current) === -1) {
            $this->response->setReturnData($progressObj)->fetch();
        }

        $progressObj->setCurrent($current)->setNext(!feof($handle));
        $progressObj->process();
        
        if (!$progressObj->getStop()) {
            $progressObj->setNext(!feof($handle));
        }

        $csv = null;

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

        $class = 'fpcm\\model\\'. str_replace('__', '\\', $item);
        if (!is_subclass_of($class, '\fpcm\model\interfaces\isCsvImportable')) {
            $this->response->setReturnData(new \fpcm\view\message('Ungültiger Typ: ' . $class, \fpcm\view\message::TYPE_ERROR ))->fetch();
        }
        
        $this->instance = new $class;
        return true;
    }


}

?>