<?php

/**
 * Public tweet template file object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Tweet Template Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class tweet extends template {
    
    const TEMPLATE_ID = 'tweet';

    /**
     * Template-Platzhalter
     * @var array
     */
    protected $replacementTags = array(
        '{{headline}}' => '',
        '{{author}}' => '',
        '{{date}}' => '',
        '{{changeDate}}' => '',
        '{{permaLink}}' => '',
        '{{shortLink}}' => ''
    );

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct('common' . DIRECTORY_SEPARATOR . 'tweet.html');
    }

    /**
     * Parst Template-Platzhalter
     * @return bool
     */
    public function parse()
    {

        if (!count($this->replacementTags) || !$this->content) {
            return false;
        }

        $this->replacementTags = $this->events->trigger('template\parseTweet', $this->replacementTags);

        $content = $this->content;
        $tags = array_merge($this->replacementInternal, $this->replacementTags);
        foreach ($tags as $replacement => $value) {
            $replacement = explode(':', $replacement);
            $content = str_replace($replacement, $value, $content);
        }

        return $content;
    }

    /**
     * Speichert Template in Dateisystem
     * @return bool
     */
    public function save()
    {
        $this->content = $this->events->trigger('template\save', array('file' => $this->fullpath, 'content' => strip_tags($this->content)))['content'];
        return parent::save();
    }

}

?>