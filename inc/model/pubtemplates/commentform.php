<?php

/**
 * Public comment form template file object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Kommentar Form Template Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class commentform extends template {
    
    const TEMPLATE_ID = 'commentForm';
    const SAVE_ERROR_FORMURL = -1001;
    const SAVE_ERROR_PRIVACY = -1002;

    /**
     * Template-Platzhalter
     * @var array
     */
    protected $replacementTags = array(
        '{{formHeadline}}' => '',
        '{{submitUrl}}' => '',
        '{{nameDescription}}' => '',
        '{{nameField}}' => '',
        '{{emailDescription}}' => '',
        '{{emailField}}' => '',
        '{{websiteDescription}}' => '',
        '{{websiteField}}' => '',
        '{{textfield}}' => '',
        '{{smileysDescription}}' => '',
        '{{smileys}}' => '',
        '{{tags}}' => '',
        '{{spampluginQuestion}}' => '',
        '{{spampluginField}}' => '',
        '{{privateCheckbox}}' => '',
        '{{submitButton}}' => '',
        '{{resetButton}}' => '',
        '{{privacyComfirmation}}' => '',
    );

    /**
     * Konstruktor
     * @param string $fileName
     */
    public function __construct($fileName = null)
    {
        if (!$fileName) {
            $fileName = 'comment_form';
        }

        parent::__construct('common' . DIRECTORY_SEPARATOR . $fileName . '.html');
    }

    /**
     * Parst Template-Platzhalter
     * @return boolean
     */
    public function parse()
    {
        if (!count($this->replacementTags) || !$this->content) {
            return false;
        }

        $this->replacementTags = $this->events->trigger('template\parseCommentForm', $this->replacementTags);
        $tags = array_merge($this->replacementInternal, $this->replacementTags);
        return str_replace(array_keys($tags), array_values($tags), $this->content);
    }

    /**
     * Speichert Template in Dateisystem
     * @return boolean
     */
    public function save()
    {
        if (!$this->exists() || !$this->content) {
            return false;
        }

        $this->content = $this->events->trigger('template\save', array('file' => $this->fullpath, 'content' => $this->content))['content'];

        if (strpos($this->content, '{{submitUrl}}') === false) {
            trigger_error('Unable to update comment form template, {{submitUrl}} replacement is missing!');
            return self::SAVE_ERROR_FORMURL;
        }

        if (strpos($this->content, '{{privacyComfirmation}}') === false) {
            trigger_error('Unable to update comment form template, {{privacyComfirmation}} replacement is missing!');
            return self::SAVE_ERROR_PRIVACY;
        }

        if (!file_put_contents($this->fullpath, $this->content)) {
            trigger_error('Unable to update template ' . $this->fullpath);
            return false;
        }

        return true;
    }

}

?>