<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub;

/**
 * Template manager file object
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
class file implements \JsonSerializable {

    use \fpcm\model\traits\jsonSerializeReturnObject;

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
     * Last change time
     * @var string
     */
    private string $changedTime = '';

    /**
     * Readable flag
     * @var bool
     */
    private bool $readable = false;

    /**
     * Writable Flag
     * @var bool
     */
    private bool $writable = false;

    /**
     * Constructor
     * @param string $fullPath
     */
    public function __construct(string $fullPath)
    {
        $this->fullPath = $fullPath;

        if (!$this->isValidPath()) {
            throw new \Exception(sprintf('Invalid data path %s!', $this->fullPath));
        }

        $this->parentFolder = basename(dirname($fullPath));
        $this->filename = basename($fullPath);
        $this->hash = \fpcm\classes\tools::getHash($fullPath);
        $this->readable = is_readable($this->fullPath);
        $this->writable = is_writable($this->fullPath);
        $this->changedTime = (string) new \fpcm\view\helper\dateText(filectime($this->fullPath));

    }

    /**
     * Full path
     * @return string
     */
    public function getFullPath(): string {
        return $this->fullPath;
    }

    /**
     * File name
     * @return string
     */
    public function getFilename(): string {
        return $this->filename;
    }

    /**
     * Parent folder name
     * @return string
     */
    public function getParentFolder(): string {
        return $this->parentFolder;
    }

    /**
     * SHA256 hash on path
     * @return string
     */
    public function getHash(): string {
        return $this->hash;
    }

    /**
     * File is writable
     * @return bool
     */
    public function isWritable() : bool
    {
        return $this->writable;
    }

    /**
     * File is readable
     * @return bool
     */
    public function isReadable() : bool
    {
        return $this->readable;
    }

    /**
     * Full path is valid data folder path
     * @return bool
     */
    public function isValidPath() : bool
    {
        return \fpcm\model\files\ops::isValidDataFolder(
            $this->fullPath,
            \fpcm\classes\dirs::DATA_STYLES
        );
    }

}
