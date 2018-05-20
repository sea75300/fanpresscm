<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\modules;

/**
 * AJAX-Controller der die Aktionen im Module-Manager ausführt
 * 
 * @package fpcm\controller\ajax\modules\moduleactions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class fetchList extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\common\dataView;

    /**
     *
     * @var int
     */
    protected $tab;

    /**
     *
     * @var string
     */
    protected $mode;

    /**
     *
     * @var \fpcm\module\modules
     */
    protected $modules;

    /**
     *
     * @var array
     */
    protected $installed;

    /**
     *
     * @var array
     */
    protected $permArr = [];

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return [
            'system' => 'options',
            'modules' => 'configure'
        ];
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath()
    {
        return '';
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        $this->mode = $this->getRequestVar('mode', [
            \fpcm\classes\http::FILTER_FIRSTUPPER
        ]);

        $fn = 'fetch'.$this->mode;
        if (!method_exists($this, $fn)) {
            $this->returnData['exec'] = 0;
            $this->getSimpleResponse();
        }

        return call_user_func([$this, $fn]);
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->initDataView();
        
        $dvVars = $this->dataView->getJsVars();
        $dbName = $this->getDataViewName();
        
        $this->returnData = [
            'dataViewVars' => $dvVars['dataviews'][$dbName],
            'dataViewName' => $dbName,
            'loadTab' => $this->tab
        ];

        $this->getSimpleResponse();
    }

    /**
     * 
     * @return boolean
     */
    private function fetchLocal()
    {
        $this->tab = 0;
        $this->modules->updateFromFilesystem();
        $this->items = $this->modules->getFromDatabase();
        $this->itemsCount = count($this->items);
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function fetchRemote()
    {
        $this->tab = 1;
        $this->installed = $this->modules->getKeysFromDatabase();
        $this->items = $this->modules->getFromRepository();
        $this->itemsCount = count($this->items);
        return true;
    }

    /**
     * 
     * @return string
     */
    protected function getDataViewName()
    {
        return 'modules'.$this->mode;
    }

    /**
     * 
     * @return array
     */
    protected function getDataViewCols()
    {
        $fn = 'getCols'.$this->mode;
        if (!method_exists($this, $fn)) {
            $this->returnData['exec'] = 0;
            $this->getSimpleResponse();
        }        
        
        return call_user_func([$this, $fn]);
    }

    /**
     * 
     * @return array
     */
    private function getColsLocal()
    {
        return [
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize('05')->setAlign('center'),
            (new \fpcm\components\dataView\column('buttons', ''))->setAlign('center')->setSize(3),
            (new \fpcm\components\dataView\column('key', 'MODULES_LIST_KEY'))->setAlign('center')->setSize(3),
            (new \fpcm\components\dataView\column('description', 'MODULES_LIST_NAME'))->setAlign('center')->setSize(3),
            (new \fpcm\components\dataView\column('version', 'MODULES_LIST_VERSION_LOCAL'))->setAlign('center')->setSize(2)
        ];
    }

    /**
     * 
     * @return array
     */
    private function getColsRemote()
    {
        return [
            (new \fpcm\components\dataView\column('buttons', ''))->setAlign('center')->setSize(2),
            (new \fpcm\components\dataView\column('key', 'MODULES_LIST_KEY'))->setAlign('center')->setSize(4),
            (new \fpcm\components\dataView\column('description', 'MODULES_LIST_NAME'))->setAlign('center')->setSize(4),
            (new \fpcm\components\dataView\column('version', 'MODULES_LIST_VERSION_REMOTE'))->setAlign('center')->setSize(2)
        ];
    }

    /**
     * 
     * @param \fpcm\module\module $item
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($item)
    {
        $fn = 'initRow'.$this->mode;
        if (!method_exists($this, $fn)) {
            $this->returnData['exec'] = 0;
            $this->getSimpleResponse();
        }        

        return call_user_func([$this, $fn], $item);
    }

    /**
     * 
     * @param \fpcm\module\module $item
     * @return \fpcm\components\dataView\row
     */
    protected function initRowLocal($item)
    {
        $config = $item->getConfig();
        
        $key = $config->key;
        $hash = \fpcm\classes\tools::getHash($key);
        
        $buttons = [];        
        if (!$item->isInstallable()) {
            $buttons[] = (new \fpcm\view\helper\icon('exclamation-triangle'))->setText('MODULES_FAILED_DEPENCIES')->setClass('fpcm-ui-padding-lg-right fpcm-ui-important-text');
        }

        $buttons[] = '<div class="fpcm-ui-controlgroup">';
        $buttons[] = (new \fpcm\view\helper\button('info'.$hash))
                            ->setText('MODULES_LIST_INFORMATIONS')
                            ->setIcon('info-circle')
                            ->setClass('fpcm-ui-modulelist-info')
                            ->setIconOnly(true)
                            ->setData([
                                'name' => (string) new \fpcm\view\helper\escape($config->name),
                                'descr' => $config->description,
                                'author' => (string) new \fpcm\view\helper\escape($config->author),
                                'link' => $config->link,
                                'php' => $config->requirements['php'],
                                'system' => $config->requirements['system']
                            ]);
        
        $hasUpdates = $this->permArr['canInstall'] && $item->hasUpdates();
        if ($item->isInstalled()) {
            
            if ($this->permArr['canConfigure']) {
                $buttons[]  = $item->isActive()
                            ? (new \fpcm\view\helper\button('disable'.$hash))->setText('MODULES_LIST_DISABLE')->setIcon('toggle-off')->setIconOnly(true)->setData(['key' => $item->getKey(), 'action' => 'disable'])->setClass('fpcm-ui-modulelist-action-local')
                            : (new \fpcm\view\helper\button('enable'.$hash))->setText('MODULES_LIST_ENABLE')->setIcon('toggle-on')->setIconOnly(true)->setData(['key' => $item->getKey(), 'action' => 'enable'])->setClass('fpcm-ui-modulelist-action-local');
            }
            

            if ($this->permArr['canUninstall'] && !$item->isActive()) {
                $buttons[] = (new \fpcm\view\helper\button('uninstall'.$hash))->setText('MODULES_LIST_UNINSTALL')->setIcon('minus-circle')->setIconOnly(true)->setData(['key' => $item->getKey(), 'action' => 'uninstall'])->setClass('fpcm-ui-modulelist-action-local');
            }

            if ($hasUpdates) {
                $buttons[] = (new \fpcm\view\helper\linkButton('update'.$hash))
                        ->setUrl(\fpcm\classes\tools::getFullControllerLink('package/modupdate', ['key' => $item->getKey()]))
                        ->setText('MODULES_LIST_UPDATE')
                        ->setIcon('sync')
                        ->setIconOnly(true);
            }
        }
        
        if (!$item->isInstalled()) {
            if ($this->permArr['canInstall']) {
                $buttons[] = (new \fpcm\view\helper\button('install'.$hash))->setText('MODULES_LIST_INSTALL')->setIcon('plus-circle')->setIconOnly(true)->setData(['key' => $item->getKey(), 'action' => 'install', 'dir' => true])->setClass('fpcm-ui-modulelist-action-local')->setReadonly(!$item->isInstallable());
            }            

            if ($this->permArr['canUninstall']) {
                $buttons[] = (new \fpcm\view\helper\button('delete'.$hash))->setText('MODULES_LIST_DELETE')->setIcon('trash')->setIconOnly(true)->setData(['key' => $item->getKey(), 'action' => 'delete'])->setClass('fpcm-ui-modulelist-action-local');
            }            
        }
        

        $buttons[] = '</div>';

        $class = ($hasUpdates ? 'fpcm-ui-important-text' : '');
        
        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('modulekeys[]', 'chbx'.$hash))->setClass('fpcm-ui-list-checkbox')->setValue($key), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('buttons', implode('', $buttons)),
            new \fpcm\components\dataView\rowCol('key', new \fpcm\view\helper\escape($key), $class ),
            new \fpcm\components\dataView\rowCol('description', new \fpcm\view\helper\escape($config->name ), $class ),
            new \fpcm\components\dataView\rowCol('version', new \fpcm\view\helper\escape($config->version), $class )
        ]);
    }

    /**
     * 
     * @param \fpcm\module\repoModule $item
     * @return \fpcm\components\dataView\row
     */
    protected function initRowRemote($item)
    {
        $config = $item->getConfig();
        
        $key = $config->key;
        $hash = \fpcm\classes\tools::getHash($key);
        
        $buttons = [];        
        if (!$item->isInstallable()) {
            $buttons[] = (new \fpcm\view\helper\icon('exclamation-triangle'))->setText('MODULES_FAILED_DEPENCIES')->setClass('fpcm-ui-padding-lg-right fpcm-ui-important-text');
        }

        $buttons[] = '<div class="fpcm-ui-controlgroup">';

        $buttons[] = (new \fpcm\view\helper\button('info'.$hash))
                    ->setText('MODULES_LIST_INFORMATIONS')
                    ->setIcon('info-circle')
                    ->setClass('fpcm-ui-modulelist-info')
                    ->setIconOnly(true)
                    ->setData([
                        'name' => (string) new \fpcm\view\helper\escape($config->name),
                        'descr' => $config->description,
                        'author' => (string) new \fpcm\view\helper\escape($config->author),
                        'link' => $config->link,
                        'php' => $config->requirements['php'],
                        'system' => $config->requirements['system']
                    ]);

        if ($this->permArr['canInstall'] && !in_array($item->getKey(), $this->installed) ) {
            $buttons[] = (new \fpcm\view\helper\linkButton('install'.$hash))
                    ->setUrl(\fpcm\classes\tools::getFullControllerLink('package/modinstall', ['key' => $item->getKey()]))
                    ->setText('MODULES_LIST_INSTALL')
                    ->setIcon('plus-circle')
                    ->setIconOnly(true)
                    ->setClass('fpcm-ui-modulelist-action-remote')
                    ->setReadonly(!$item->isInstallable());
        }

        $buttons[] = '</div>';

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('buttons', implode('', $buttons)),
            new \fpcm\components\dataView\rowCol('key', new \fpcm\view\helper\escape($key) ),
            new \fpcm\components\dataView\rowCol('description', new \fpcm\view\helper\escape($config->name ) ),
            new \fpcm\components\dataView\rowCol('version', new \fpcm\view\helper\escape($config->version) )
        ]);
    }

    /**
     * 
     * @return boolean
     */
    protected function initActionObjects()
    {
        $this->modules = new \fpcm\module\modules();

        $this->permArr = [
            'canInstall' => $this->permissions->check(['modules' => 'install']),
            'canUninstall' => $this->permissions->check(['modules' => 'uninstall']),
            'canConfigure' => $this->permissions->check(['modules' => 'configure']),
        ];

        return true;
    }

}

?>
