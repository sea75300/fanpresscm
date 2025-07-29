<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\editor;

/**
 * ACE editor based editor plugin
 *
 * @package fpcm\components\editor
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since  5.3.0-dev
 */
class aceEditor extends articleEditor {

    /**
     * Files list label name
     */
    const FILELIST_LABEL = 'label';

    /**
     * Files list value name
     */
    const FILELIST_VALUE = 'value';

    /**
     * Link target literals
     */
    const LINK_TARGETS = [
        '_blank' => '_blank',
        '_top' => '_top',
        '_self' => '_self',
        '_parent' => '_parent'
    ];

    /**
     * Align literals
     */
    const LINK_ALIGNS = [
        'EDITOR_HTML_BUTTONS_ALEFT' => 'left',
        'EDITOR_HTML_BUTTONS_ACENTER' => 'center',
        'EDITOR_HTML_BUTTONS_ARIGHT' => 'right'
    ];

    protected array $styles = [];

    /**
     * Liefert zu ladender CSS-Dateien für Editor zurück
     * @return array
     */
    public function getCssFiles()
    {
        return [
            \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_THEME, 'ace.css')
        ];
    }

    /**
     * Pfad der Editor-Template-Datei
     * @return string
     */
    public function getEditorTemplate()
    {
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'articles/editors/ace.php');
    }

    /**
     * Pfad der Kommentar-Editor-Template-Datei
     * @return string
     */
    public function getCommentEditorTemplate()
    {
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'comments/editors/ace.php');
    }

    /**
     * Liefert zu ladender Javascript-Dateien für Editor zurück
     * @return array
     */
    public function getJsFiles()
    {
        return [
            \fpcm\classes\dirs::getLibUrl('nkorg/jscharmap/charmap.js'),
            \fpcm\classes\dirs::getLibUrl('ace/ace.js'),
            \fpcm\classes\dirs::getLibUrl('ace/ext-language_tools.js'),
            \fpcm\classes\dirs::getLibUrl('ace/ext-inline_autocomplete.js'),
            'editor/filemanager.js',
            'editor/ace.js'
        ];
    }

    /**
     * Array von Javascript-Variablen, welche in Editor-Template genutzt werden
     * @return array
     */
    public function getJsVars()
    {
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
    public function getJsLangVars()
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
        $this->styles = $this->getEditorStyles();

        $vars = new conf\aceVars($this->styles);

        $ev = $this->events->trigger('editor\initAceEditorView', $vars);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event editor\initAceEditorView failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return $vars;
        }

        return $ev->getData();

    }

    /**
     * Arary mit Informationen u. a. für template-Plugin von TinyMCE
     * @see \fpcm\model\abstracts\articleEditor::getTemplateDrafts()
     * @return array
     */
    public function getTemplateDrafts()
    {
        $templatefilelist = new \fpcm\model\files\templatefilelist();

        $ret = [];
        foreach ($templatefilelist->getFolderList() as $file) {

            $basename = basename($file);

            if ($basename === 'index.html') {
                continue;
            }

            $ret[$basename] = $basename;
        }

        return $ret;
    }

    public function getDialogs() : array
    {
        return [
            $this->getLinkDialog(),
            $this->getImageDialog(),
            $this->getMediaDialog(),
            $this->getColorDialog(),
            $this->getQuoteDialog()
        ];
    }

    private function getLinkDialog() : \fpcm\view\helper\dialog
    {
        $fields = [
            (new \fpcm\view\helper\textInput('links[url]'))
                ->setType('url')
                ->setValue('')
                ->setText('EDITOR_LINKURL')
                ->setIcon('external-link-alt')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            (new \fpcm\view\helper\textInput('links[text]'))
                ->setText('EDITOR_LINKTXT')
                ->setIcon('keyboard')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            [
                (new \fpcm\view\helper\select('links[target]'))
                    ->setOptions(self::LINK_TARGETS)
                    ->setText('EDITOR_LINKTARGET')
                    ->setIcon('window-restore')
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
                (new \fpcm\view\helper\textInput('links[rel]'))
                    ->setText('EDITOR_LINKREL')
                    ->setIcon('cog')
                    ->setLabelTypeFloat()
                    ->setBottomSpace('')
            ]
        ];

        if (count($this->styles)) {
            $fields[] = (new \fpcm\view\helper\select('links[css]'))
                ->setOptions($this->styles)
                ->setText('EDITOR_CSS_CLASS')
                ->setIcon('paint-roller')
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_PLEASESELECT)
                ->setLabelTypeFloat()
                ->setBottomSpace('');
        }

        return (new \fpcm\view\helper\dialog('insertLink'))->setFields($fields);
    }

    private function getImageDialog() : \fpcm\view\helper\dialog
    {
        $fields = [
            (new \fpcm\view\helper\textInput('images[path]'))
                ->setType('url')
                ->setValue('')
                ->setText('EDITOR_IMGPATH')
                ->setIcon('image')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            (new \fpcm\view\helper\textInput('images[alt]'))
                ->setText('EDITOR_IMGALTTXT')
                ->setIcon('keyboard')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            (new \fpcm\view\helper\select('images[align]'))
                ->setOptions(self::LINK_ALIGNS)
                ->setText('EDITOR_IMGALIGN')
                ->setIcon('align-center')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
        ];

        if (count($this->styles)) {
            $fields[] = (new \fpcm\view\helper\select('images[css]'))
                ->setOptions($this->styles)
                ->setText('EDITOR_CSS_CLASS')
                ->setIcon('paint-roller')
                ->setLabelTypeFloat()
                ->setBottomSpace('');
        }

        $fields[] = [
            (new \fpcm\view\helper\numberInput('images[width]'))
                ->setText('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH')
                ->setIcon('arrows-left-right')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            (new \fpcm\view\helper\numberInput('images[height]'))
                ->setText('SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT')
                ->setIcon('arrows-up-down')
                ->setLabelTypeFloat()
                ->setBottomSpace('')
        ];

        return (new \fpcm\view\helper\dialog('insertImage'))->setFields($fields);
    }

    private function getMediaDialog() : \fpcm\view\helper\dialog
    {

        $playerFormats = $this->language->translate('EDITOR_INSERTMEDIA_FORMATS');

        $fields = [
            [
                (new \fpcm\view\helper\textInput('media[path]', 'mediapath'))
                    ->setType('url')
                    ->setValue('')
                    ->setText('EDITOR_IMGPATH')
                    ->setIcon('film ')
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
                (new \fpcm\view\helper\select('media[format]', 'mediaformat'))
                    ->setText('EDITOR_INSERTMEDIA_FORMAT_SELECT')
                    ->setOptions($playerFormats)
                    ->setClass('fpcm-editor-mediaformat')
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
            ],
            [
                (new \fpcm\view\helper\textInput('media[path]', 'mediapath2'))
                    ->setType('url')
                    ->setValue('')
                    ->setText('EDITOR_IMGPATH_ALT')
                    ->setIcon('file-video')
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
                (new \fpcm\view\helper\select('media[format2]', 'mediaformat2'))
                    ->setText('EDITOR_INSERTMEDIA_FORMAT_SELECT')
                    ->setOptions($playerFormats)
                    ->setClass('fpcm-editor-mediaformat')
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
            ],
            [
                (new \fpcm\view\helper\textInput('media[poster]', 'mediaposter'))
                    ->setType('url')
                    ->setValue('')
                    ->setText('EDITOR_INSERTMEDIA_POSTER')
                    ->setIcon('file-image')
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
                (new \fpcm\view\helper\button('insertposterimg', 'insertposterimg'))
                    ->setText('HL_FILES_MNG')
                    ->setIcon('folder-open')
                    ->setIconOnly()
            ],
            [
                (new \fpcm\view\helper\radiobutton('mediatype', 'mediatypea'))
                    ->setText('EDITOR_INSERTMEDIA_AUDIO')
                    ->setClass('fpcm-editor-mediatype')
                    ->setValue('audio')
                    ->setSelected(true)
                    ->setSwitch(true)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
                (new \fpcm\view\helper\radiobutton('mediatype', 'mediatypev'))
                    ->setText('EDITOR_INSERTMEDIA_VIDEO')
                    ->setClass('fpcm-editor-mediatype')
                    ->setValue('video')
                    ->setSwitch(true)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
                (new \fpcm\view\helper\checkbox('controls', 'controls'))
                    ->setText('EDITOR_INSERTMEDIA_CONTROLS')
                    ->setValue(1)
                    ->setSelected(true)
                    ->setSwitch(true)
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
                (new \fpcm\view\helper\checkbox('autoplay', 'autoplay'))
                    ->setText('EDITOR_INSERTMEDIA_AUTOPLAY')
                    ->setValue(1)
                    ->setSwitch(true)
                    ->setLabelTypeFloat()
                    ->setBottomSpace('')
            ]
        ];

        return (new \fpcm\view\helper\dialog('insertMedia'))->setFields($fields);
    }

    private function getColorDialog() : \fpcm\view\helper\dialog
    {
        $fields = [
            (new \fpcm\view\helper\textInput('colorhexcode'))
                ->setValue('#000000')
                ->setType('color')
                ->setText('EDITOR_INSERTCOLOR_HEXCODE')
                ->setIcon('eye-dropper')
                ->setSize('lg')
                ->setLabelTypeFloat(),
            [
                (new \fpcm\view\helper\radiobutton('color_mode', 'color_mode1'))
                    ->setText('EDITOR_INSERTCOLOR_TEXT')
                    ->setClass('fpcm-ui-editor-colormode')
                    ->setValue('color')
                    ->setSelected(true)
                    ->setSwitch(true),
                (new \fpcm\view\helper\radiobutton('color_mode', 'color_mode2'))
                    ->setText('EDITOR_INSERTCOLOR_BACKGROUND')
                    ->setClass('fpcm-ui-editor-colormode')
                    ->setValue('background')
                    ->setSwitch(true)
            ]
        ];

        return (new \fpcm\view\helper\dialog('insertColor'))->setFields($fields);
    }

    private function getQuoteDialog() : \fpcm\view\helper\dialog
    {
        $fields = [
            (new \fpcm\view\helper\textarea('quote[text]'))
                ->setPlaceholder(true)
                ->setText('EDITOR_HTML_BUTTONS_QUOTE_TEXT')
                ->setIcon('keyboard')
                ->setClass('fpcm ui-textarea-medium ui-textarea-noresize')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            (new \fpcm\view\helper\textInput('quote[src]'))
                ->setValue('')
                ->setText('TEMPLATE_ARTICLE_SOURCES')
                ->setIcon('external-link-alt')
                ->setSize('lg')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            [
                (new \fpcm\view\helper\radiobutton('quote[type]', 'quotetype1'))
                    ->setText('EDITOR_HTML_BUTTONS_QUOTE_BLOCK')
                    ->setClass('fpcm-ui-editor-quotemode')
                    ->setValue('blockquote')
                    ->setSelected(true)
                    ->setSwitch(true)
                    ->setBottomSpace(''),
                (new \fpcm\view\helper\radiobutton('quote[type]', 'quotetype2'))
                    ->setText('EDITOR_HTML_BUTTONS_QUOTE_INLINE')
                    ->setClass('fpcm-ui-editor-quotemode')
                    ->setValue('q')
                    ->setSwitch(true)
                    ->setBottomSpace('')
            ]
        ];

        return (new \fpcm\view\helper\dialog('insertQuote'))->setFields($fields);
    }

}
