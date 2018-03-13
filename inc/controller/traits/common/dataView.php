<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * Dataview trait
 * 
 * @package fpcm\controller\traits\common
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
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
     * @see controller::getViewPath
     * @return string
     */
    protected function getViewPath()
    {
        return 'components/dataview';
    }

    /**
     * Initialize Data view object
     * @return boolean
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
                    new \fpcm\components\dataView\rowCol('col', 'GLOBAL_NOTFOUND2', 'fpcm-ui-padding-md-lr'),
                ],
                '',
                false,
                true
            ));
            
            $this->view->addDataView($this->dataView);
            return true;
        }
        
        foreach ($this->items as $item) {
            
            $row = $this->initDataViewRow($item);
            if (!$row) {
                continue;
            }
            
            $this->dataView->addRow($row);
        }

        $this->view->addDataView($this->dataView);
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

}

?>