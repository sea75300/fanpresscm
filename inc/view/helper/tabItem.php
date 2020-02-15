<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Tab item
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\view\helper
 * @since FPCM 4
 */
class tabItem extends helper {

    /**
     * Url
     * @var string
     */
    protected $url = '';

    /**
     * File path
     * @var string
     */
    protected $file = '';

    /**
     * Tab-ID
     * @var string
     */
    protected $dataViewId = '';

    /**
     * Returns item id
     * @return string
     */
    public function getDataViewId()
    {
        return $this->dataViewId;
    }

    /**
     * Set tab URL
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * File path
     * @param string $file
     * @return $this
     * @since FPCm 4.3
     */
    public function setFile(string $file) 
    {
        $this->url = '#'.$this->id;
        $this->file = $file;
        return $this;
    }

    /**
     * Tab required file inclution
     * @return bool
     * @since FPCm 4.3
     */
    public function getFile() : string
    {
        return $this->file;
    }

    /**
     * Set item ID
     * @param string $dataViewId
     * @return $this
     */
    public function setDataViewId($dataViewId)
    {
        $this->dataViewId = $dataViewId;
        return $this;
    }

    /**
     * Return item string
     * @return string
     */
    protected function getString()
    {
        $html = [];
        $html[] = '<li';
        $html[] = 'id="fpcm-tabs-'.$this->id.'" class="ui-tabs-tab ui-corner-top ui-state-default"';

        if ($this->dataViewId) {
            $html[] = 'data-dataview-list="'.$this->dataViewId.'"';
        }

        if (!$this->useWrapper) {
            $html[] = $this->getDataString();
        }

        $html[] = '><a class="ui-tabs-anchor" href="'.$this->url.'" '.($this->useWrapper ? $this->getDataString() : '').'>'.$this->text.'</a>';
        $html[] = '</li>';

        return implode(' ', $html);
    }

}
