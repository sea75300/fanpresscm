<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Backdrop image file object
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class backdropImage {

    /**
     * Image file path
     * @var string
     */
    private $path = null;

    /**
     * Image url
     * @var string
     */
    private $url = '';

    /**
     * Credit file path
     * @var ?string
     */
    private $creditFilePath = null;

    /**
     * File was selected
     * @var bool
     */
    private $selected = null;

    public function __construct(bool $fallback = false)
    {
        $bg = \fpcm\model\system\session::getInstance()?->getCurrentUser()?->getUserMeta()?->backdrop;
        if (!trim($bg) && !$fallback) {
            return;
        }
        elseif(!trim($bg) && $fallback) {
            $bg = 'pexels-asad-photo-maldives-4578810.jpg';
        }
        
        $bg = 'backdrops/' . $bg;
        
        $this->url = \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_THEME, $bg);
        $this->path = realpath(\fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_THEME, $bg));
        $this->creditFilePath = $this->path . '.txt';
    }

    /**
     * Check if backdrop exists
     * @return bool
     */
    public function hasBackdrop() : bool
    {
        if ($this->selected === null) {
            $this->selected = $this->path && $this->creditFilePath && str_starts_with($this->path, \fpcm\classes\dirs::getCoreDirPath('/theme/backdrops'));
        }

        return $this->selected;
    }

    /**
     * Returns image url
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * Return credit .txt file content
     * @return string
     */
    public function getCredits() : string
    {
        return $this->hasBackdrop() && file_exists($this->creditFilePath) ? file_get_contents($this->creditFilePath) : '';
    }

}