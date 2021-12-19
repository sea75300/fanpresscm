<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * AJAX import controller
 * 
 * @package fpcm\controller\ajax\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class import extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     * 
     * @var \fpcm\model\interfaces\isCsvImportable
     */
    private $instance;

    /**
     * 
     * @var \fpcm\model\files\fileOption
     */
    private $opt;

    private $item;

    private $current;
    
    private $next;
    
    private $file;

    private $delim;

    private $enclosure;

    private $skipfirst;

    private $fields;

    private $unique;

    private $preview;

    private $reset;
    
    private $responseData = [];

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

        $this->preview = (bool) $this->request->fromPOST('preview');

        $this->reset = (bool) $this->request->fromPOST('reset');

        $this->unique = $this->request->fromPOST('unique');
        if (!trim($this->unique)) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('IMPORT_MSG_INITFAILED.'),
                \fpcm\view\message::TYPE_ERROR,
                \fpcm\view\message::ICON_ERROR
            ))->fetch();
        }

        $this->opt = new \fpcm\model\files\fileOption('csv/'.$this->unique);
        $csvParams = !$this->preview && $this->opt->read() !== null ? (array) $this->opt->read() : $this->request->fromPOST('csv');
        $this->opt->write($csvParams);

        $this->item = filter_var($csvParams['item'] ?? null, FILTER_SANITIZE_STRING);

        $this->file = basename(filter_var($csvParams['file'] ?? null, FILTER_SANITIZE_STRING), '.csv');

        $this->delim = substr(filter_var($csvParams['delim'] ?? null, FILTER_SANITIZE_STRING), 0, 1);
        if (!in_array($this->delim, [';', ','])) {
            $this->delim = ';';
        }
        
        $this->enclosure = substr(filter_var($csvParams['enclo'] ?? null, FILTER_SANITIZE_STRING), 0, 1);
        if (!in_array($this->enclosure, ['"', '\''])) {
            $this->enclosure = '"';
        }
        
        $this->skipfirst = $csvParams['skipfirst'] ?? false;
        
        if ($this->reset) {
            return true;
        }

        $this->fields = $csvParams['fields'] ?? [];
        if (!count($this->fields)) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('IMPORT_MSG_NOFIELDS'),
                \fpcm\view\message::TYPE_ERROR,
                \fpcm\view\message::ICON_ERROR
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
        $this->responseData = [
            'fs' => 0,
        ];

        $uploadUnique = \fpcm\classes\tools::getHash($this->session->getSessionId().$this->session->getUserId());
        
        $csv = new \fpcm\model\files\csvFile($uploadUnique . DIRECTORY_SEPARATOR . $this->file, $this->delim, $this->enclosure);
        
        if ($this->reset) {
            if ($csv->exists()) $csv->delete();
            $this->opt->remove();
            $this->response->setReturnData(['reset' => 1])->fetch();
        }
        
        if ( !$csv->exists() || !$csv->isValidDataFolder('', \fpcm\classes\dirs::DATA_TEMP ) ) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('IMPORT_MSG_CSVNOTFOUND'),
                \fpcm\view\message::TYPE_ERROR,
                \fpcm\view\message::ICON_ERROR
            ))->fetch();
        }

        if ( \fpcm\model\files\csvFile::isValidType($csv->getExtension(), $csv->getMimeType())  ) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('IMPORT_MSG_CSVINVALID'),
                \fpcm\view\message::TYPE_ERROR,
                \fpcm\view\message::ICON_ERROR
            ))->fetch();            
        }

        $i = 0;
        
        $progressObj = new \fpcm\model\system\progress(function (&$data, &$current, $next, &$stop) use (&$csv, &$i) {

            if ($current >= $data['fs']) {
                $stop = true;
                return false;
            }

            $i++;
            
            $line = $csv->getContent();
            
            if ($this->skipfirst && $i < 2) {
                $current = $csv->tell();
                return !$csv->isEoF() ? true : false;
            }

            if (!is_array($line)) {
                return !$csv->isEoF() ? true : false;
            }
            
            if ($csv->assignCsvFields($this->fields, $line) === false) {

                if (!$this->preview) $csv->delete();

                $this->response->setReturnData(new \fpcm\view\message(
                    $this->language->translate('IMPORT_MSG_EMPTYFIELD'),
                    \fpcm\view\message::TYPE_ERROR,
                    \fpcm\view\message::ICON_ERROR
                ))->fetch();
            }

            if ($this->preview) {
                
                if (!isset($data['previews'])) {
                    
                    $fields = array_map(function($field) {                       
                        \fpcm\model\files\csvFile::prepareFieldName($field);
                        return $field;
                    }, $this->fields);

                    $data['previews'] = [
                        array_intersect($this->instance->getFields(), $fields)
                    ];
                }
                
                $data['previews'][] = $line;
                
                if (count($data['previews']) >= 10) {
                    $stop = true;
                    $current = 0;
                    return false;
                }

                return true;
            }
            
            if (!$this->instance->assignCsvRow($line)) {

                $csv->delete();

                $this->response->setReturnData(new \fpcm\view\message(
                    $this->language->translate('IMPORT_MSG_FAILEDSAVE'),
                    \fpcm\view\message::TYPE_ERROR,
                    \fpcm\view\message::ICON_ERROR
                ))->fetch();
            }
            
            $current = $csv->tell();
            usleep(500);

            return !$csv->isEoF() ? true : false;
        }, $this->unique);

        $progressObj->setNext($this->next)->setData($this->responseData);

        if (!$csv->hasResource()) {
            
            $csv->delete();

            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('IMPORT_MSG_NOHANDLE'),
                \fpcm\view\message::TYPE_ERROR,
                \fpcm\view\message::ICON_ERROR
            ))->fetch();
        }

        $this->responseData['fs'] = $csv->getFilesize();
        $progressObj->setNext($this->next)->setData($this->responseData);

        if (!$progressObj->getNext()) {
            $this->response->setReturnData($progressObj)->fetch();
        }

        if ($csv->seek($this->current) === -1) {
            $this->response->setReturnData($progressObj)->fetch();
        }

        $progressObj->setCurrent($this->current)->setNext(!$csv->isEoF());
        $progressObj->process();
        
        if (!$progressObj->getStop()) {
            $progressObj->setNext(!$csv->isEoF());
        }
        else if (!$this->preview) {
            $csv->delete();
            $this->opt->remove();
        }

        $csv = null;
        $this->opt = null;

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

        $class = 'fpcm\\model\\'. str_replace('__', '\\', $this->item);
        if (!is_subclass_of($class, '\fpcm\model\interfaces\isCsvImportable')) {
            $this->response->setReturnData(new \fpcm\view\message(
                $this->language->translate('IMPORT_MSG_INVALIDIMPORTTYPE', [
                    'importtype' => str_replace('__', '\\', $this->item)
                ]),
                \fpcm\view\message::TYPE_ERROR,
                \fpcm\view\message::ICON_ERROR
            ))->fetch();
        }

        $this->instance = new $class;
        return true;
    }


}

?>