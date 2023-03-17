<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * Test SMTP connection
 * 
 * @package fpcm\controller\ajax\system\passcheck
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.0.1
 */
class smtpTest extends \fpcm\controller\abstracts\ajaxController
{
    /**
     * Check controlelr acccess
     * @return boolean
     */
    public function isAccessible(): bool
    {
        return $this->config->smtp_enabled && $this->permissions->system->options;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $mail = new \fpcm\classes\email($this->config->system_email, 'Test message', 'This is a FanPress CM SMTP Testmessage!');
        if (!$mail->checkSmtp() || !$mail->submit()) {
            $msg = new \fpcm\view\message($this->language->translate('SAVE_FAILED_OPTIONS_SMTP_CONECT') , \fpcm\view\message::TYPE_ERROR);
        }
        else {
            $msg = new \fpcm\view\message($this->language->translate('SYSTEM_OPTIONS_EMAIL_ACTIVE'), \fpcm\view\message::TYPE_NOTICE);
        }
            
        $this->response->setReturnData($msg)->fetch();
    }

}
