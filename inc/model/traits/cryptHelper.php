<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;

/**
 * Crypt helper trait
 * 
 * @package fpcm\model\traits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1.1
 */
trait cryptHelper {

    /**
     * Do encryption
     * @var bool
     */
    private $doCrypt = false;
    
    /**
     * Returns \fpcm\classes\crypt class instance
     * @return \fpcm\classes\crypt
     */
    private function getCryptInstance() : \fpcm\classes\crypt
    {
        
        return \fpcm\classes\loader::getObject('\\fpcm\\classes\\crypt');
    }

    /**
     * Encrypts data
     * @param mixed $param
     * @return string
     */
    protected function encrypt($param) : mixed
    {
        if (!$this->doCrypt) {
            return $param;
        }
        
        return $this->getCryptInstance()->encrypt($param);
    }

    /**
     * Decrypts data
     * @param mixed $param
     * @return string
     */
    protected function decrypt($param) : mixed
    {
        if (!$this->doCrypt) {
            return $param;
        }

        return $this->getCryptInstance()->decrypt($param);
    } 

}
