<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\editor;

/**
 * Article editor plugin base model
 * 
 * @abstract
 * @package fpcm\components\editor
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.1.0
 */
abstract class articleEditor extends \fpcm\model\abstracts\staticModel {

    /**
     * Dateilisten-Objekt
     * @var \fpcm\model\files\imagelist
     */
    protected $fileList;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileList = new \fpcm\model\files\imagelist();
    }

    /**
     * Pfad der Editor-Template-Datei
     * @return string
     */
    abstract public function getEditorTemplate();

    /**
     * Pfad der Kommentar-Editor-Template-Datei
     * @return string
     */
    abstract public function getCommentEditorTemplate();

    /**
     * Liefert zu ladender Javascript-Dateien für Editor zurück
     * @return array
     */
    abstract public function getJsFiles();

    /**
     * Liefert zu ladender CSS-Dateien für Editor zurück
     * @return array
     */
    abstract public function getCssFiles();

    /**
     * Array von Javascript-Variablen, welche in Editor-Template genutzt werden
     * @return array
     */
    abstract public function getJsVars();

    /**
     * Array von Sprachvariablen für Nutzung in Javascript
     * @return array
     * @since 3.3
     */
    abstract public function getJsLangVars();

    /**
     * Array von Variablen, welche in Editor-Template genutzt werden
     * @return array
     */
    abstract public function getViewVars();

    /**
     * Array mit Informationen u. a. für template-Plugin von TinyMCE
     * @return array
     * @since 3.3
     */
    abstract public function getTemplateDrafts();
    
    /**
     * Editor-Styles initialisieren
     * @return array
     */
    protected function getEditorStyles()
    {
        if (!trim($this->config->system_editor_css)) {
            return [];
        }

        $classes = explode(PHP_EOL, $this->config->system_editor_css);

        $editorStyles = [];
        foreach ($classes as $class) {
            $class = trim(str_replace(array('.', '{', '}'), '', $class));
            $editorStyles[$class] = $class;
        }

        return $this->events->trigger('editor\addStyles', $editorStyles)->getData();
    }

    /**
     * Editor-Links initialisieren
     * @return string
     */
    public function getEditorLinks()
    {
        $links = $this->events->trigger('editor\addLinks')->getData();
        if (!is_array($links) || !count($links)) {
            return [];
        }

        return $links;
    }

    /**
     * Dateiliste initialisieren
     * @return array
     */
    public function getFileList()
    {
        $data = [];
        foreach ($this->fileList->getDatabaseList() as $image) {

            $base = basename($image->getFilename());

            $data[] = [   
                static::FILELIST_LABEL => $image->getAltText() ? $image->getAltText() . " ({$base})" : $base,
                static::FILELIST_VALUE => $image->getImageUrl()
            ];

        }

        $res = $this->events->trigger('editor\getFileList', [
            'label' => 'label',
            'files' => $data
        ])->getData();

        return $res['files'] ?? [];
    }

    /**
     * Gibt Textpattern-Konfiguration zurück,
     * nur in TinyMCE genutzt
     * @return array
     */
    protected function getTextPatterns()
    {
        return [
            ['start' => '- ', 'cmd' => 'InsertUnorderedList'],
            ['start' => '* ', 'cmd' => 'InsertUnorderedList'],
            ['start' => '# ', 'cmd' => 'InsertOrderedList'],
            ['start' => '1. ', 'cmd' => 'InsertOrderedList'],
            ['start' => '_', 'end' => '_', 'format' => 'italic'],
            ['start' => '*', 'end' => '*', 'format' => 'bold'],
        ];
    }

}
