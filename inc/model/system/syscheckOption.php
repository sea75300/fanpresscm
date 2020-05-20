<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system;

/**
 * System config Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class syscheckOption {

    /**
     * Current value
     * @var string
     */
    protected $current  = '';
    
    /**
     * Help link
     * @var string
     */
    protected $helplink = '';
    
    /**
     * Check result
     * @var bool
     */
    protected $result   = false;
    
    /**
     * Option is not required
     * @var bool
     */
    protected $optional = false;
    
    /**
     * Option is checked folder
     * @var bool
     */
    protected $isFolder = false;
    
    /**
     * Option is checked folder
     * @var \fpcm\view\helper\linkButton
     */
    protected $actionButton;
    
    /**
     * Add Notice
     * @var string
     */
    protected $notice       = '';

    /**
     * Konstruktor
     * @param string $current
     * @param string $helplink
     * @param bool $result
     * @param bool $optional
     * @param bool $isFolder
     */
    public function __construct($current, $helplink, $result, $optional = false, $isFolder = false)
    {
        $this->current  = $current;
        $this->helplink = $helplink;
        $this->result   = (bool) $result;
        $this->optional = (bool) $optional;
        $this->isFolder = (bool) $isFolder;
    }

    /**
     * Return button code
     * @return string
     */
    public function getActionButton()
    {
        return $this->actionButton;
    }

    /**
     * Set Action Button
     * @param \fpcm\view\helper\linkButton $actionButton
     */
    public function setActionButton(\fpcm\view\helper\linkButton $actionButton)
    {
        $this->actionButton = $actionButton;
    }

    /**
     * Returns notice
     * @return string
     */
    public function getNotice()
    {
        return $this->notice;
    }

    /**
     * Set notice
     * @param string $notice
     */
    public function setNotice($notice)
    {
        $this->notice = $notice;
    }
        
    /**
     * Returns current value
     * @return string
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Returns info/help link
     * @return string
     */
    public function getHelplink()
    {
        return $this->helplink;
    }

    /**
     * Returns check value
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Returns optional flasg
     * @return bool
     */
    public function getOptional()
    {
        return $this->optional;
    }

    /**
     * Returns if folder was checked
     * @return string
     */
    public function isFolder()
    {
        return $this->isFolder;
    }

    /**
     * Returns check string for cli
     * @param string $descr
     * @return string
     */
    public function asString($descr)
    {
        $line = str_pad($descr, 55, ' ',  STR_PAD_RIGHT).": {$this->current}".($this->result ? '' : ' !!!');
        if (!$this->notice) {
            return $line;
        }

        return $line.PHP_EOL.$this->notice;
    }

}
