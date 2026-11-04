<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub;


/**
 * Template file wrapper
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
class file {

    /**
     * Fulle path
     * @var string
     */
    private string $fullPath = '';

    /**
     * Filename
     * @var string
     */
    private string $filename = '';

    /**
     * Parent folder name
     * @var string
     */
    private string $parentFolder = '';

    /**
     * Path hash
     * @var string
     */
    private string $hash = '';

    /**
     * Constructor
     * @param string $fullPath
     */
    public function __construct(string $fullPath)
    {
        $this->fullPath = $fullPath;
        $this->parentFolder = basename(dirname($fullPath));
        $this->filename = basename($fullPath);
        $this->hash = \fpcm\classes\tools::getHash($fullPath);
    }

    public function getFullPath(): string {
        return $this->fullPath;
    }

    public function getFilename(): string {
        return $this->filename;
    }

    public function getParentFolder(): string {
        return $this->parentFolder;
    }

    public function getHash(): string {
        return $this->hash;
    }
    

}
