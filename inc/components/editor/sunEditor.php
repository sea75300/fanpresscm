<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\editor;

/**
 * Sun Editor based editor plugin
 *
 * @package fpcm\components\editor
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since  5.3.0-dev
 */
class sunEditor extends articleEditor {

    /**
     * Files list label name
     */
    const FILELIST_LABEL = 'label';

    /**
     * Files list value name
     */
    const FILELIST_VALUE = 'value';

    /**
     * Liefert zu ladender CSS-Dateien für Editor zurück
     * @return array
     */
    public function getCssFiles() : array
    {
        return [
            \fpcm\classes\dirs::getLibUrl('sun-editor/dist/css/suneditor.min.css'),
        ];
    }

    /**
     * Pfad der Editor-Template-Datei
     * @return string
     */
    public function getEditorTemplate()
    {
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'articles/editors/suneditor.php');
    }

    /**
     * Pfad der Kommentar-Editor-Template-Datei
     * @return string
     */
    public function getCommentEditorTemplate()
    {
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'comments/editors/suneditor.php');
    }

    /**
     * Liefert zu ladender Javascript-Dateien für Editor zurück
     * @return array
     */
    public function getJsFiles() : array
    {
        return [
            \fpcm\classes\dirs::getLibUrl('sun-editor/dist/suneditor.min.js'),
            \fpcm\classes\dirs::getLibUrl(sprintf('sun-editor/dist/lang/%s.js', $this->config->system_lang)),
            'files/fileProperties.js',
            'editor/filemanager.js',
            'editor/suneditor.js'
        ];
    }

    /**
     * Array von Javascript-Variablen, welche in Editor-Template genutzt werden
     * @return array
     */
    public function getJsVars() : array
    {
        return [
            'editorConfig' => new conf\sun($this->config)
        ];
        
        $cfg = [
            'editorConfig' => [
                'ace' => new conf\ace($this->config),
                'colors' => [
                    '#000000', '#993300', '#333300', '#003300', '#003366', '#00007f', '#333398', '#333333',
                    '#800000', '#ff6600', '#808000', '#007f00', '#007171', '#0000e8', '#5d5d8b', '#6c6c6c',
                    '#f00000', '#e28800', '#8ebe00', '#2f8e5f', '#30bfbf', '#3060f1', '#770077', '#8d8d8d',
                    '#f100f1', '#f0c000', '#eeee00', '#00f200', '#00efef', '#00beee', '#8d2f5e', '#b5b5b5',
                    '#ed8ebe', '#efbf8f', '#e8e88b', '#bbeabb', '#bcebeb', '#89b6e4', '#b88ae6', '#ffffff'
                ],
                'autosavePref' => 'fpcm-editor-as-' . $this->session->getUserId() . 'draft',
                'pageBreakVar' => \fpcm\model\pubtemplates\article::PAGEBREAK_TAG,
                'drafts' => $this->getTemplateDrafts()
            ]
        ];

        $ev = $this->events->trigger('editor\initAceEditor', $cfg);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event editor\initAceEditor failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return $cfg;
        }

        return $ev->getData();
    }

    /**
     * Array von Sprachvariablen für Nutzung in Javascript
     * @see \fpcm\model\abstracts\articleEditor
     * @return array
     */
    public function getJsLangVars() : array
    {
        return [
            'GLOBAL_INSERT', 'EDITOR_INSERTPIC', 'EDITOR_INSERTLINK',
            'EDITOR_INSERTTABLE', 'EDITOR_INSERTCOLOR', 'EDITOR_INSERTMEDIA',
            'EDITOR_INSERTSMILEY', 'EDITOR_HTML_BUTTONS_ARTICLETPL',
            'EDITOR_HTML_BUTTONS_LISTUL', 'EDITOR_HTML_BUTTONS_LISTOL',
            'EDITOR_HTML_BUTTONS_QUOTE', 'EDITOR_INSERTSYMBOL',
            'EDITOR_INSERTSYMBOL_CHARS', 'EDITOR_INSERTSYMBOL_MATH',
            'EDITOR_INSERTSYMBOL_MISC', 'EDITOR_INSERTSYMBOL_ARROWS',
            'GLOBAL_PREVIEW', 'EDITOR_INSERTPIC_ASLINK',
            'EDITOR_HTML_BUTTONS_IFRAME', 'EDITOR_LINKURL',
            'EDITOR_INSERTTABLE_ROWS', 'EDITOR_INSERTTABLE_COLS',
            'EDITOR_INSERTLIST_TYPESIGN', 'EDITOR_HTML_BUTTONS_ALEFT',
            'EDITOR_HTML_BUTTONS_ACENTER', 'EDITOR_HTML_BUTTONS_ARIGHT'
        ];
    }
    
    /**
     * Returns editor variables data
     * @return \fpcm\components\editor\conf\aceVars
     */
    public function getViewVars()
    {
        return [];
        
        $this->styles = $this->getEditorStyles();

        $vars = new conf\aceVars($this->styles);

        $ev = $this->events->trigger('editor\initAceEditorView', $vars);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event editor\initAceEditorView failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return $vars;
        }

        return $ev->getData();

    }

}
