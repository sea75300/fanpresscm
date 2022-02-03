<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * Module data path handler
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\module
 * @since 5.0.0-b1
 */
class paths {
    
    /**
     * 
     * @var string
     */
    private $basePath;

    /**
     * Constructor
     * @param module $obj
     */
    final public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    final public function readFile(string $filename)
    {
        if (!file_exists($this->basePath . $filename)) {
            trigger_error('File not found: ' . $this->basePath . $filename);
            return null;
        }
        
        if (!is_readable($this->basePath . $filename)) {
            trigger_error('File not readable: ' . $this->basePath . $filename);
            return null;            
        }
        
        return file_get_contents($this->basePath . $filename);
    }

    final public function writeFile(string $filename, $data, $mode = FILE_APPEND)
    {
        if (!file_exists($this->basePath . $filename)) {
            trigger_error('File not found: ' . $this->basePath . $filename);
            return false;
        }
        
        if (!is_writable($this->basePath . $filename)) {
            trigger_error('File not writable: ' . $this->basePath . $filename);
            return false;            
        }
        
        return file_put_contents($this->basePath . $filename, $data, $mode);
    }

    final public function deleteFile(string $filename)
    {
        if (!file_exists($this->basePath . $filename)) {
            trigger_error('File not found: ' . $this->basePath . $filename);
            return false;
        }
        
        if (!is_writable($this->basePath . $filename)) {
            trigger_error('File not writable: ' . $this->basePath . $filename);
            return false;            
        }
        
        return unlink($this->basePath . $filename);
    }

}
