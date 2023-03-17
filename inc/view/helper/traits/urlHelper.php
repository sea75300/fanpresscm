<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper\traits;

/**
 * View helper with url
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait urlHelper {

    /**
     * Link URL
     * @var string
     */
    protected $url = '';

    /**
     * Link URL target
     * @var string
     */
    protected $target = '';

    /**
     * Set link url
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set link target
     * @param string $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * 
     * @return string
     * @since 5.1.0-b4
     */
    protected function getTargetString() : string
    {
        if (!$this->target) {
            return '';
        }
        
        return sprintf('target="%s"', $this->target);
    }

}
