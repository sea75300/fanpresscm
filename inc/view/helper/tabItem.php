<?php

/**
 * FanPress CM 5
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
 * @since 4
 */
class tabItem extends helper {

    /**
     * @var int Tab state active
     * @since 5.0-dev
     */
    const STATE_ACTIVE = 1;

    /**
     * @var int Tab state disabled
     * @since 5.0-dev
     */
    const STATE_DISABLED = 2;
    
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
     * Module key
     * @var string
     * @since 5.0.-dev
     */
    protected $modulekey = '';

    /**
     * Tab status
     * @var int
     * @since 5.0-dev
     */
    protected $state = 011;

    /**
     * Preload tab item
     * @var bool
     * @since 5.0-dev
     */
    protected $preload = true;

    /**
     * Optional init function
     * @return void
     * @ignore
     */
    protected function init()
    {
        $this->data['bs-toggle'] = 'tab';
    }

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
     * @since 4.3
     */
    public function setFile(string $file) 
    {
        if (substr($file, 0, 2) === '{$') {
            $file = \fpcm\classes\tools::strReplaceArray($file, [
                \fpcm\view\view::PATH_COMPONENTS => \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'components' . DIRECTORY_SEPARATOR),
                \fpcm\view\view::PATH_MODULE => $this->modulekey ? \fpcm\module\module::getTemplateDirByKey($this->modulekey, DIRECTORY_SEPARATOR) : '',
            ]);
        }
        
        if (substr($file, -4) !== '.php') {
            $file .= '.php';
        }

        if (!file_exists($file)) {
            $file = realpath(\fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, $file));
        }

        $this->url = '#';
        $this->file = $file;

        return $this;
    }

    /**
     * 
     * @param int $state
     * @return $this
     * @since 5.0-dev
     */
    public function setState(int $state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * 
     * @param string $modulekey
     * @return $this
     * @since 5.0-dev
     */
    public function setModulekey(string $modulekey)
    {
        $this->modulekey = $modulekey;
        return $this;
    }

    /**
     * Tab required file inclution
     * @return bool
     * @since 4.3
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
     * Set item ID
     * @param string $dataViewId
     * @return $this
     */
    public function setTabToolbar($toolbarTab)
    {
        $this->data['toolbar-buttons'] = (string) $toolbarTab;
        return $this;
    }

    /**
     * Set item index
     * @param int $index
     * @return $this
     */
    public function setSaveIndex(int $index)
    {
        $this->data['tab-index'] = $index;
        return $this;
    }

    /**
     * Set preload status of tab
     * @param bool $preload
     * @return $this
     * @since 5.0-dev
     */
    public function setPreload(bool $preload) {
        $this->preload = $preload;
        return $this;
    }
    
    /**
     * Get item ID
     * @return string
     */
    final public function getId()
    {
        return $this->id;
    }

    /**
     * Return active state of tab
     * @return bool
     * @since 5.0-dev
     */
    final public function isActive() : bool
    {
        return $this->state === self::STATE_ACTIVE;
    }

    /**
     * Return preload state of tab
     * @return bool
     * @since 5.0-dev
     */
    final public function canPreload() : bool
    {
        return $this->preload;
    }

    /**
     * Return item string
     * @return string
     */
    protected function getString()
    {
        $html = [];
        $html[] = '<li id="fpcm-tab-'.$this->id.'" class="nav-item flex-sm-fill11"';

        if ($this->dataViewId) {
            $this->data['dataview-list'] = $this->dataViewId;
        }

        if (!$this->useWrapper) {
            $html[] = $this->getDataString();
        }

        $this->data['bs-target'] = '#fpcm-tab-'.$this->id.'-pane';
        $this->data['bs-toggle'] = 'tab';

        $css = 'fpcm ui-background-white-50p ';

        switch ($this->state) {
            case self::STATE_ACTIVE :
                $css .= 'active';
                $this->setAria(['current' => 'page']);                
                break;
            case self::STATE_DISABLED :
                $css .= 'disabled';
                $this->setAria(['disabled' => 'true']);                
                break;
        }

        $html[] = '><a class="nav-link '.$css.'" href="'.$this->url.'" role="tab" '.$this->getDataString().' '.$this->getAriaString().'>'.$this->text.'</a>';
        $html[] = '</li>';

        return implode(' ', $html);
    }

}
