<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * Dataview trait
 * 
 * @package fpcm\controller\traits\common
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait dataView {

    /**
     * Data view items array
     * @var array
     */
    protected $items = [];

    /**
     * Data view object
     * @var int
     */
    protected $itemsCount = null;

    /**
     * Returns default description string for "No entries found"
     * @return string
     */
    public function getNotFoundDesription() : string
    {
        return (new \fpcm\view\helper\icon('list-ul '))->setSize('lg')->setStack(true)->setStack('ban fpcm-ui-important-text')->setStackTop(true).' '.
               $this->language->translate('GLOBAL_NOTFOUND2');
    }

    /**
     * @see controller::getViewPath
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'components/dataview';
    }

    /**
     * Initialize Data view object
     * @return bool
     */
    protected function initDataView()
    {
        $this->dataView = new \fpcm\components\dataView\dataView($this->getDataViewName());    

        if ($this->itemsCount === null) {
            $this->itemsCount = count($this->items);
        }
        
        $this->dataView->addColumns($this->getDataViewCols());

        if (!$this->itemsCount) {

            $this->dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol(
                        'col',
                        $this->getNotFoundDesription(),
                        '',
                        \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT
                    ),
                ],
                '',
                false,
                true
            ));
            
            if (isset($this->view) && $this->view instanceof \fpcm\view\view) {
                $this->view->addDataView($this->dataView);

                $tabs = $this->getDataViewTabs();
                if (count($tabs)) {
                    $this->view->addTabs('tabs-'.$this->getDataViewName(), $tabs);
                }
            }

            return true;
        }
        
        foreach ($this->items as $item) {
            
            $row = $this->initDataViewRow($item);
            if (!$row) {
                continue;
            }
            
            $this->dataView->addRow($row);
        }

        
        if (isset($this->view) && $this->view instanceof \fpcm\view\view) {
            $this->view->addDataView($this->dataView);
            
            $tabs = $this->getDataViewTabs();
            if (count($tabs)) {
                $this->view->addTabs('tabs-'.$this->getDataViewName(), $tabs);
            }
        }
        
    }

    /**
     * Get data view name
     * @return string
     */
    abstract protected function getDataViewName();

    /**
     * Get data view Columns
     * @return array
     */
    abstract protected function getDataViewCols();

    /**
     * Get data view Columns
     * @return array
     */
    protected function getDataViewTabs() : array
    {
        return [];
    }
    
    /**
     * Initialize Data view row
     * @param mixed $item
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($item)
    {
        return null;
    }

}

?>