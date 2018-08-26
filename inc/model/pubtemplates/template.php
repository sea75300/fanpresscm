<?php

/**
 * Public template file object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * generisches Template Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class template extends \fpcm\model\abstracts\file {

    /**
     * erlaubte Template-Tags
     * @var array
     */
    protected $allowedTags = array(
        '<div>', '<span>', '<p>', '<b>', '<strong>', '<i>', '<em>', '<u>', '<a>', '<h1>', '<h2>', '<h3>', '<h4>', '<h5>',
        '<h6>', '<img>', '<table>', '<tr>', '<td>', '<br>', '<form>', '<input>', '<button>', '<select>', '<option>',
        '<ul>', '<ol>', '<li>', '<script>', '<iframe>', '<label>'
    );

    /**
     * Template-Platzhalter
     * @var array
     */
    protected $replacementTags = [];

    /**
     * Interne Platzhalter
     * @var array
     */
    protected $replacementInternal = [];

    /**
     * Platzhalter mit Sprachbezeichner
     * @var array
     * @since FPCM 3.5.2
     */
    protected $replacementTranslated = [];

    /**
     * 
     * Konstruktor
     * @param string $filename
     */
    public function __construct($filename = '')
    {
        parent::__construct($filename);
        $this->init(null);
    }

    /**
     * 
     * @param string $filename
     * @return string
     */
    protected function basePath($filename)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_STYLES, $filename);
    }

    /**
     * Gibt erlaubte HTML-Tags als string zurück
     * @param string $delim
     * @return string
     */
    public function getAllowedTags($delim = '')
    {
        return implode($delim, $this->allowedTags);
    }

    /**
     * Liefert Platzhalter zurück
     * @return array
     */
    public function getReplacementTags()
    {
        return $this->replacementTags;
    }

    /**
     * Liefert interne Platzhalter zurück
     * @return array
     */
    public function getReplacementInternal()
    {
        return $this->replacementInternal;
    }

    /**
     * Fügt erlaubte HTML-Tags hinzu
     * @param array $allowedTags
     */
    public function setAllowedTags(array $allowedTags)
    {
        $this->allowedTags = array_merge($this->allowedTags, $allowedTags);
    }

    /**
     * Fügt Platzhalter hinzu
     * @param array $replacementTags
     */
    public function setReplacementTags(array $replacementTags)
    {
        $this->replacementTags = $replacementTags;
    }

    /**
     * nicht verwendet
     * @return boolean
     */
    public function delete()
    {
        return false;
    }

    /**
     * nicht verwendet
     * @param string $newname
     * @param int $userId
     * @return boolean
     */
    public function rename($newname, $userId = false)
    {
        return false;
    }

    /**
     * Datei-Inhalt festlegen
     * @param string $content
     */
    public function setContent($content)
    {
        parent::setContent(strip_tags($content, $this->getAllowedTags()));
    }

    /**
     * Datei-Inhalt zurückgeben
     * @return string
     */
    public function getContent()
    {
        return strip_tags(parent::getContent(), $this->getAllowedTags());
    }

    /**
     * Speichert Template in Dateisystem
     * @return boolean
     */
    public function save()
    {
        if (!$this->exists() || !$this->content || !$this->isWritable()) {
            return false;
        }

        $this->content = $this->events->trigger('template\save', ['file' => $this->fullpath, 'content' => $this->content])['content'];

        if (!file_put_contents($this->fullpath, $this->content)) {
            trigger_error('Unable to update template ' . $this->fullpath);
            return false;
        }

        $this->cache->cleanup();

        return true;
    }

    /**
     * Parst Template-Platzhalter
     * @return string
     */
    public function parse()
    {
        if (!count($this->replacementTags) || !$this->content) {
            return false;
        }

        $this->replacementTags = $this->events->trigger('template\parse', $this->replacementTags);

        $tags = array_merge($this->replacementInternal, $this->replacementTags);
        return str_replace(array_keys($tags), array_values($tags), $this->content);
    }

    /**
     * Platzhalter-Übersetzungen
     * @param string $prefix
     * @return array
     * @since FPCM 3.5.2
     */
    public function getReplacementTranslations($prefix)
    {
        if (count($this->replacementTranslated)) {
            return $this->replacementTranslated;
        }

        foreach ($this->replacementTags as $key => $value) {
            $data = explode(':', strtoupper(str_replace(['{{', '}}'], '', $key)));
            $this->replacementTranslated[$key] = $this->language->translate($prefix . $data[0]);
        }

        return $this->replacementTranslated;
    }

    /**
     * Parst Smileys in Artikeln und Kommentaren
     * @param string $content
     * @return string
     */
    protected function parseSmileys($content)
    {
        if ($this->cache->isExpired('smileyCache')) {
            $smileysList = new \fpcm\model\files\smileylist();
            $smileys = $smileysList->getDatabaseList();
            $this->cache->write('smileyCache', $smileys, $this->config->system_cache_timeout);
        } else {
            $smileys = $this->cache->read('smileyCache');
        }

        foreach ($smileys as $smiley) {
            $content = str_replace($smiley->getSmileyCode(), $this->parseSmileyFilePath($smiley), $content);
        }

        return $content;
    }

    /**
     * Parst Smileys
     * @param \fpcm\model\files\smiley $smiley
     * @return string
     */
    private function parseSmileyFilePath(\fpcm\model\files\smiley $smiley)
    {
        return '<img src="' . $smiley->getSmileyUrl() . '" class="fpcm-pub-smiley" ' . $smiley->getWhstring() . ' alt="">';
    }

    /**
     * Initialisiert Template-Inhalt
     * @param void $initDB
     */
    protected function init($initDB)
    {
        if (!$this->exists()) {
            return false;
        }

        $this->content = file_get_contents($this->fullpath);
        $this->allowedTags = $this->events->trigger('pub\templateHtmlTags', $this->allowedTags);
    }

    /**
     * Links in Text parsen
     * @param string $content
     * @param array $attributes
     * @param bool $returnOnly
     * @return string
     */
    protected function parseLinks(&$content, array $attributes = [], $returnOnly = false)
    {
        $attrs = '';
        foreach ($attributes as $attribute => $value) {
            $attrs .= " {$attribute}=\"{$value}\"";
        }

        $regEx = '/((http|https?):\/\/\S+[^\s.,>)\]\"\'<\/])/is';

        if ($returnOnly) {
            preg_match_all($regEx, $content, $matches);
            return $matches;
        }

        $content = preg_replace($regEx, "<a href=\"$0\"{$attrs}>$0</a>", $content);

        return $content;
    }

    /**
     * 
     * @return boolean
     * @ignore
     */
    protected function parseReplacement()
    {
        $re = '/^(\{\{)([a-zA-Z]+)(\s)(.+)(\}\})$/m';
        $str = '{{privacyComfirmation test="bla" test2="lalala"}}';

        preg_match_all($re, $str, $matches);
        fpcmDump($matches);
        return true;
    }

}

?>