<?php

/**
 * Option edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system\testing;

class smtpOAuth extends \fpcm\controller\abstracts\controller
{

    /**
     * 
     * @return bool
     */
    public function isAccessible() : bool
    {
        return false; //$this->permissions->system->options && $this->config->smtp_enabled && $this->config->smtp_settings->auth == 'XOAUTH2';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        fpcmLogSystem( [
            __METHOD__,
            $this->request->fetchAll(null)
        ] );
    }

}
