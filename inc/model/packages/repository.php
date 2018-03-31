<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\packages;

/**
 * Repository class
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class repository extends \fpcm\model\abstracts\remoteModel {

    const FOPT_UPDATES = 'updates.yml';

    const FOPT_MODULES = 'modules.yml';
    
    /**
     *
     * @var array
     */
    private $files = [];

    /**
     *
     * @var array
     */
    private $current = '';

    /**
     * 
     * @return boolean
     */
    public function __construct()
    {
        parent::__construct();

        $this->files = [
            \fpcm\classes\baseconfig::$updateServer.'release.yml' => self::FOPT_UPDATES,
            \fpcm\classes\baseconfig::$moduleServer.'release.yml' => self::FOPT_MODULES
        ];

        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function fetchRemoteData()
    {
        foreach ($this->files as $rem => $local) {            
            
            fpcmLogSystem('Fetch package information from '.$rem);

            $this->remoteServer = $rem;
            $this->current      = $local;

            $success = parent::fetchRemoteData();
            if ($success !== true) {
                return $success;
            }

            if (!$this->saveRemoteData()) {
                return false;
            }
        }

        return true;
    }

    /**
     * 
     * @return type
     */
    protected function saveRemoteData()
    {
        $storage = new \fpcm\model\files\fileOption($this->current);
        return $storage->write($this->remoteData);
    }

}

?>