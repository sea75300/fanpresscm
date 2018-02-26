<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * Zeitzonen trait
 * 
 * @package fpcm\controller\traits\common\timezone
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait dataView {

    /**
     * Data view object
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     * @see controller::getViewPath
     * @return string
     */
    protected function getViewPath()
    {
        return 'common/dataView';
    }

    /**
     * Initialize Data view object
     * @return boolean
     */
    abstract protected function initDataView();

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