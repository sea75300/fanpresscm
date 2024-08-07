<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * E-Mail-Objekt
 * 
 * @package fpcm\classes\email
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class email {

    /**
     * Empfänger
     * @var string
     */
    private $to = '';

    /**
     * Absender
     * @var string
     */
    private $from = '';

    /**
     * Betreff
     * @var string
     */
    private $subject = '';

    /**
     * E-Mail-Text
     * @var string
     */
    private $text = '';

    /**
     * Headers
     * @var array
     */
    private $headers = [];

    /**
     * E-Mail-Anhänge
     * @var array
     * @since 3.6
     */
    private $attachments = [];

    /**
     * HTML-E-Mail-Status
     * @var bool
     */
    private $html = false;

    /**
     * PHPMailer Object
     * @var \PHPMailer\PHPMailer\PHPMailer
     */
    private $mailer = null;

    /**
     * Config Object
     * @var \fpcm\model\system\config
     */
    private $config;

    /**
     * Konstruktor
     * @param sring $to Empfänger-Adresse
     * @param sring $subject Betreff
     * @param sring|array $text E-Mail-Inhalt
     * @param sring $from Absender-Adresse, Default: fanpresscm@@hostdomain.xyz
     * @param bool $html enthält $text HTML-Code ja/nein
     */
    function __construct($to, $subject, $text, $from = false, $html = false)
    {
        $this->to = $to;
        $this->from = $from ? $from : 'FanPress CM <fanpresscm@' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '>';
        $this->subject = $subject;
        $this->text = is_array($text) ? implode(PHP_EOL, $text) : $text;
        $this->html = $html;

        $this->config = loader::getObject('\fpcm\model\system\config');
    }

    /**
     * Empfänger auslesen
     * @return sring
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Absender auslesen
     * @return sring
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Betreff auslesen
     * @return sring
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * E-Mail-Inhalt auslesen
     * @return sring
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Array mit Dateipfaden der Anhänge auslesen
     * @return array
     * @since 3.6
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * HTMl-E-Mail ja/nein
     * @return bool
     */
    public function isHtml()
    {
        return $this->html;
    }

    /**
     * Empfänger setzen
     * @param sring $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * Absender setzen
     * @param sring $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * Betreff setzen
     * @param sring $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * E-Mail-Inhalt setzen
     * @param sring $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * E-Mail- als HTML-E-Mail markieren
     * @param bool $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * Array mit Pfaden der Anhänge setzen
     * @param array $attachments
     * @since 3.6
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * Versendet E-Mail
     * @return bool
     */
    public function submit()
    {
        $eventData = loader::getObject('\fpcm\events\events')->trigger('emailSubmit', [
            'headers' => $this->headers,
            'maildata' => [
                'to' => $this->to,
                'from' => $this->from,
                'subject' => $this->subject,
                'text' => $this->text,
            ],
            'attachments' => $this->attachments
        ]);
        
        if (is_object($eventData) && $eventData instanceof \fpcm\module\eventResult) {
            $eventData = $eventData->getData();
        }

        $this->headers = $eventData['headers'];
        $this->attachments = $eventData['attachments'];
        foreach ($eventData['maildata'] as $key => $value) {
            $this->$key = $value;
        }

        $this->getMailerObj();

        $recipients = explode('; ', $this->to);
        foreach ($recipients as $recipient) {
            $recipient = explode(' <', $recipient, 3);
            $this->mailer->addAddress($recipient[0], isset($recipient[1]) ? trim(str_replace(['<', '>'], '', $recipient[1])) : '');
        }

        $this->mailer->Subject = $this->subject;
        $this->mailer->Body = $this->text;
        if ($this->html) {
            $this->mailer->AltBody = $this->mailer->html2text($this->text);
        }
        $this->mailer->XMailer = 'FanPressCM' . $this->config->system_version;
        $this->mailer->CharSet = 'utf-8';

        if (count($this->headers)) {
            foreach ($this->headers as $name => $value) {
                $this->mailer->addCustomHeader($name, $value);
            }
        }

        if (count($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $this->mailer->addAttachment($attachment);
            }
        }

        return call_user_func([$this, 'submit' . ($this->config->smtp_enabled ? 'Smtp' : 'Php')]);
    }

    /**
     * SMTP-Zugangsdaten testen
     * @return bool
     * @since 3.5
     */
    public function checkSmtp()
    {

        if (!$this->config->smtp_enabled) {
            return false;
        }

        $this->getMailerObj();
        $this->initSmtpSettings();

        try {
            $res = $this->mailer->smtpConnect();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            trigger_error("Unable to send SMTP e-mail \"{$this->subject}\" to \"{$this->to}\".\n----------\n{$this->text}\n----------\n\n{$this->mailer->ErrorInfo}\n----------\n\n" . $e->getTraceAsString());
            return false;
        }

        $this->mailer->smtpClose();
        return $res ? true : false;
    }

    /**
     * 
     * @param string $path
     * @param array $variables
     * @param bool $fromData
     * @return bool
     * @since 5.1.0-a1
     */
    public function fromTemplate(string $path, array $variables = [], bool $fromData = false) : bool
    {
        $filename = $path . ($this->html ? '.html' : '.txt');

        $tplPath = $fromData
                 ? dirs::getDataDirPath(dirs::DATA_BACKUP, $filename)
                 : dirs::getCoreDirPath(dirs::CORE_VIEWS, 'mailtemplates/' . $filename);

        $tplPathR = realpath($tplPath);
        if (!$tplPathR || !str_starts_with($tplPath, \fpcm\classes\dirs::getFullDirPath('/')) ) {
            trigger_error(sprintf('Invalid mail template path found in "%s".', $tplPath), E_USER_ERROR);
            $this->text = '';
            return false;
        }
        
        $tplPath = $tplPathR;
        
        $this->text = file_get_contents($tplPath);
        if (!trim($this->text)) {
            trigger_error(sprintf('Unable to read mail template content from "%s".', $tplPath), E_USER_ERROR);
            $this->text = '';
            return false;
        }

        $this->text = vsprintf($this->text, $variables);
        if (!trim($this->text)) {
            trigger_error(sprintf('Unable render mail template content "%s".', $tplPath), E_USER_ERROR);
            $this->text = '';
            return false;
        }

        return true;
    }

    /**
     * E-Mail versenden via PHP versenden
     * @return bool
     * @since 3.5
     */
    private function submitPhp()
    {

        try {
            $res = $this->mailer->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            trigger_error("Unable to send PHP e-mail \"{$this->subject}\" to \"{$this->to}\".\n----------\n{$this->text}\n----------\n\n{$this->mailer->ErrorInfo}\n----------\n\n" . $e->getTraceAsString());
            return false;
        }

        return $res ? true : false;
    }

    /**
     * E-Mail via SMTP versenden
     * @return bool
     * @since 3.5
     */
    private function submitSmtp()
    {
        $this->initSmtpSettings();

        try {
            $res = $this->mailer->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            trigger_error("Unable to send SMTP e-mail \"{$this->subject}\" to \"{$this->to}\".\n----------\n{$this->text}\n----------\n\n{$this->mailer->ErrorInfo}\n----------\n\n" . $e->getTraceAsString());
            return false;
        }

        return $res ? true : false;
    }

    /**
     * Erzeugt neues PHPMailer-Objekt
     * @return bool
     * @since 3.5
     */
    private function getMailerObj()
    {
        require_once loader::libGetFilePath('PHPMailer');

        $this->mailer = new \PHPMailer\PHPMailer\PHPMailer();
        $this->mailer->isHTML($this->html);
        $this->mailer->setFrom($this->config->smtp_settings['addr']);
        $this->mailer->setLanguage($this->config->system_lang);
        $this->mailer->Timeout = FPCM_SMTP_TIMEOUT ?? 5;        

        $this->mailer->Debugoutput = function($str, $level) {
            trigger_error($str);
        };

        return true;
    }

    /**
     * 
     * @return bool
     * @since 5.0.1
     */
    private function initSmtpSettings(): bool
    {
        if (!$this->config->smtp_enabled) {
            return false;
        }

        $this->mailer->isSMTP();

        $config = $this->config;

        $autoEncryption = ($config->smtp_settings['encr'] === 'auto' ? true : false);

        $this->mailer->Host = $config->smtp_settings['srvurl'];
        $this->mailer->Username = $config->smtp_settings['user'];
        $this->mailer->Password = $config->smtp_settings['pass'];
        $this->mailer->Port = $config->smtp_settings['port'];
        $this->mailer->SMTPSecure = !$autoEncryption ? $config->smtp_settings['encr'] : '';
        $this->mailer->SMTPAutoTLS = $autoEncryption;
        $this->mailer->SMTPAuth = ($config->smtp_settings['user'] && $config->smtp_settings['pass']) ? true : false;
        $this->mailer->SMTPDebug = 4; /* @see \PHPMailer\PHPMailer\SMTP::DEBUG_CONNECTION; */
        $this->mailer->getSMTPInstance()->Timelimit = FPCM_SMTP_TIMEOUT;

        return true;
    }
    
    /**
     * Return SMTP encryption modes
     * @return array
     * @since 4
     */
    public static function getEncryptions() : array
    {
        return [
            'SSL' => 'ssl',
            'TLS' => 'tls',
            'Auto' => 'auto'
        ];
    }
    
    /**
     * Return SMTP authentication types
     * @return array
     * @since 5
     */
    public static function getAuthenticationTypes() : array
    {
        return [
            'CRAM-MD5' => 'CRAM-MD5',
            'LOGIN' => 'LOGIN',
            'PLAIN' => 'PLAIN',
//            'XOAUTH2 (Gmail)' => 'XOAUTH2',
        ];
    }

}
