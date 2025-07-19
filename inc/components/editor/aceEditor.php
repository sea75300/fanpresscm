<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\editor;

/**
 * CodeMirror based editor plugin
 *
 * @package fpcm\components\editor
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class aceEditor extends articleEditor {

    /**
     * Files list label name
     * @since 4.5
     */
    const FILELIST_LABEL = 'label';

    /**
     * Files list value name
     * @since 4.5
     */
    const FILELIST_VALUE = 'value';

    /**
     * Link target literals
     * @since 5.3.0
     */
    const LINK_TARGETS = [
        '_blank' => '_blank',
        '_top' => '_top',
        '_self' => '_self',
        '_parent' => '_parent'
    ];

    /**
     * Align literals
     * @since 5.3.0
     */    
    const LINK_ALIGNS = [
        'left' => 'left',
        'center' => 'center',
        'right' => 'right'
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
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'comments/editors/html.php');
    }

    /**
     * Liefert zu ladender Javascript-Dateien für Editor zurück
     * @return array
     */
    public function getJsFiles()
    {
        return [
            \fpcm\classes\dirs::getLibUrl('ace/ace.js'),
            \fpcm\classes\dirs::getLibUrl('ace/ext-language_tools.js'),
            \fpcm\classes\dirs::getLibUrl('ace/ext-inline_autocomplete.js'),
            'editor/editor_filemanager.js',
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
                'ace' => [
                    'fontSize' => $this->config->system_editor_fontsize,
                    'theme' => sprintf('ace/theme/tomorrow%s', $this->config->system_darkmode ? '_night' : ''),
                    'mode' => 'ace/mode/html',
                    'enableBasicAutocompletion' => true,
                    'enableLiveAutocompletion' => true,
                    'enableSnippets' => true,
                    'wrap' => true,
                    'minLines' => 15,
                    'maxLines' => 50
                ],
                'colors' => [
                    '#000000', '#993300', '#333300', '#003300', '#003366', '#00007f', '#333398', '#333333',
                    '#800000', '#ff6600', '#808000', '#007f00', '#007171', '#0000e8', '#5d5d8b', '#6c6c6c',
                    '#f00000', '#e28800', '#8ebe00', '#2f8e5f', '#30bfbf', '#3060f1', '#770077', '#8d8d8d',
                    '#f100f1', '#f0c000', '#eeee00', '#00f200', '#00efef', '#00beee', '#8d2f5e', '#b5b5b5',
                    '#ed8ebe', '#efbf8f', '#e8e88b', '#bbeabb', '#bcebeb', '#89b6e4', '#b88ae6', '#ffffff'
                ],
                'autosavePref' => 'fpcm-editor-as-' . $this->session->getUserId() . 'draft',
                'pageBreakVar' => \fpcm\model\pubtemplates\article::PAGEBREAK_TAG,
            ],
            'editorInitFunction' => 'initAce'
        ];

        return $cfg;

        $ev = $this->events->trigger('editor\initCodemirrorJs', $cfg);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event editor\initCodemirrorJs failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return $cfg;
        }

        return $ev->getData();
    }

    /**
     * Array von Sprachvariablen für Nutzung in Javascript
     * @see \fpcm\model\abstracts\articleEditor
     * @return array
     * @since 3.3
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
            'EDITOR_INSERTLIST_TYPESIGN'

        ];
    }

    /**
     * Array von Variablen, welche in Editor-Template genutzt werden
     * @return array
     */
    public function getViewVars()
    {
        $this->styles = $this->getEditorStyles();       

        $vars = array(
            'editorStyles' => array_map(function ($val) {
                    return (new \fpcm\view\helper\dropdownItem('style-'.md5($val)))
                        ->setText($val)
                        ->setClass('fpcm-editor-html-click')
                        ->setData(['htmltag' => $val, 'action' => 'insertStyle'])
                        ->setValue(md5($val));
                },
                $this->styles
            ),
            'editorFontsizes' => array(
                (new \fpcm\view\helper\dropdownItem('fs-8pt'))
                    ->setText('8pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '8', 'action' => 'insertFontsize'])
                    ->setValue('8'),
                (new \fpcm\view\helper\dropdownItem('fs-9pt'))
                    ->setText('9pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '9', 'action' => 'insertFontsize'])
                    ->setValue('9'),
                (new \fpcm\view\helper\dropdownItem('fs-10pt'))
                    ->setText('10pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '10', 'action' => 'insertFontsize'])
                    ->setValue('10'),
                (new \fpcm\view\helper\dropdownItem('fs-11pt'))
                    ->setText('11pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '11pt', 'action' => 'insertFontsize'])
                    ->setValue('11'),
                (new \fpcm\view\helper\dropdownItem('fs-12pt'))
                    ->setText('12pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '12', 'action' => 'insertFontsize'])
                    ->setValue('12'),
                (new \fpcm\view\helper\dropdownItem('fs-13pt'))
                    ->setText('13pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '13', 'action' => 'insertFontsize'])
                    ->setValue('13'),
                (new \fpcm\view\helper\dropdownItem('fs-14pt'))
                    ->setText('14pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '14', 'action' => 'insertFontsize'])
                    ->setValue('14'),
                (new \fpcm\view\helper\dropdownItem('fs-16pt'))
                    ->setText('16pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '16', 'action' => 'insertFontsize'])
                    ->setValue('16'),
                (new \fpcm\view\helper\dropdownItem('fs-18pt'))
                    ->setText('18pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '18', 'action' => 'insertFontsize'])
                    ->setValue('18'),
                (new \fpcm\view\helper\dropdownItem('fs-20pt'))
                    ->setText('20pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '20', 'action' => 'insertFontsize'])
                    ->setValue('20'),
                (new \fpcm\view\helper\dropdownItem('fs-24pt'))
                    ->setText('24pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '24', 'action' => 'insertFontsize'])
                    ->setValue('24'),
                (new \fpcm\view\helper\dropdownItem('fs-32pt'))
                    ->setText('32pt')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => '32', 'action' => 'insertFontsize'])
                    ->setValue('32'),
            ),
            'editorParagraphs' => array(
                (new \fpcm\view\helper\dropdownItem('para-p'))
                    ->setText('EDITOR_PARAGRAPH')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => 'p'])
                    ->setValue('p'),
                (new \fpcm\view\helper\dropdownItem('para-h1'))
                    ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 1])
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => 'h1'])
                    ->setValue('h1'),
                (new \fpcm\view\helper\dropdownItem('para-h2'))
                    ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 2])
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => 'h2'])
                    ->setValue('h2'),
                (new \fpcm\view\helper\dropdownItem('para-h3'))
                    ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 3])
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => 'h3'])
                    ->setValue('h3'),
                (new \fpcm\view\helper\dropdownItem('para-h4'))
                    ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 4])
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => 'h4'])
                    ->setValue('h4'),
                (new \fpcm\view\helper\dropdownItem('para-h5'))
                    ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 5])
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => 'h5'])
                    ->setValue('h5'),
                (new \fpcm\view\helper\dropdownItem('para-h6'))
                    ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 6])
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => 'h6'])
                    ->setValue('h6'),
                (new \fpcm\view\helper\dropdownItem('para-pre'))
                    ->setText('EDITOR_PRE')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => 'pre'])
                    ->setValue('pre'),
                (new \fpcm\view\helper\dropdownItem('para-code'))
                    ->setText('code')
                    ->setClass('fpcm-editor-html-click')
                    ->setData(['htmltag' => 'code'])
                    ->setValue('code'),
            ),
            'editorDefaultFontsize' => $this->config->system_editor_fontsize,
            'editorTemplatesList' => $this->getTemplateDrafts(),
            'editorButtons' => [
                'bold' => (new \fpcm\view\helper\button('editor-html-buttonbold'))->setText('EDITOR_HTML_BUTTONS_BOLD')->setIcon('bold')->setData(['htmltag' => 'b']),
                'italic' => (new \fpcm\view\helper\button('editor-html-buttonitalic'))->setText('EDITOR_HTML_BUTTONS_ITALIC')->setIcon('italic')->setData(['htmltag' => 'i']),
                'underline' => (new \fpcm\view\helper\button('editor-html-buttonunderline'))->setText('EDITOR_HTML_BUTTONS_UNDERLINE')->setIcon('underline')->setData(['htmltag' => 'u']),
                'strike' => (new \fpcm\view\helper\button('editor-html-buttonstrike'))->setText('EDITOR_HTML_BUTTONS_STRIKE')->setIcon('strikethrough')->setData(['htmltag' => 's']),
                'delim1' => (new \fpcm\view\helper\toolbarSeperator('sep1'))->setClass(' me-1 mb-1'),
                'color' => (new \fpcm\view\helper\button('editor-html-buttoninsertcolor'))->setText('EDITOR_INSERTCOLOR')->setIcon('palette')->setData(['action' => 'insertColor']),
                'sup' => (new \fpcm\view\helper\button('editor-html-buttonsup'))->setText('EDITOR_HTML_BUTTONS_SUP')->setIcon('superscript')->setData(['htmltag' => 'sup']),
                'sub' => (new \fpcm\view\helper\button('editor-html-buttonsub'))->setText('EDITOR_HTML_BUTTONS_SUB')->setIcon('subscript')->setData(['htmltag' => 'sub']),
                'aleft' => (new \fpcm\view\helper\button('editor-html-buttonaleft'))->setText('EDITOR_HTML_BUTTONS_ALEFT')->setIcon('align-left')->setData(['htmltag' => 'left', 'action' => 'insertAlignTags']),
                'acenter' => (new \fpcm\view\helper\button('editor-html-buttonacenter'))->setText('EDITOR_HTML_BUTTONS_ACENTER')->setIcon('align-center')->setData(['htmltag' => 'center', 'action' => 'insertAlignTags']),
                'aright' => (new \fpcm\view\helper\button('editor-html-buttonaright'))->setText('EDITOR_HTML_BUTTONS_ARIGHT')->setIcon('align-right')->setData(['htmltag' => 'right', 'action' => 'insertAlignTags']),
                'ajustify' => (new \fpcm\view\helper\button('editor-html-buttonajustify'))->setText('EDITOR_HTML_BUTTONS_AJUSTIFY')->setIcon('align-justify')->setData(['htmltag' => 'justify', 'action' => 'insertAlignTags']),
                'delim2' => (new \fpcm\view\helper\toolbarSeperator('sep2'))->setClass(' me-1 mb-1'),
                'listul' => (new \fpcm\view\helper\button('editor-html-buttoninsertlist'))->setText('EDITOR_HTML_BUTTONS_LISTUL')->setIcon('list-ul')->setData(['htmltag' => 'ul', 'action' => 'insertList']),
                'listol' => (new \fpcm\view\helper\button('editor-html-buttoninsertlistnum'))->setText('EDITOR_HTML_BUTTONS_LISTOL')->setIcon('list-ol')->setData(['htmltag' => 'ol', 'action' => 'insertList']),
                'delim3' => (new \fpcm\view\helper\toolbarSeperator('sep3'))->setClass(' me-1 mb-1'),
                'quote' => (new \fpcm\view\helper\button('editor-html-buttonquote'))->setText('EDITOR_HTML_BUTTONS_QUOTE')->setIcon('quote-left')->setData(['action' => 'insertQuote']),
                'link' => (new \fpcm\view\helper\button('editor-html-buttoninsertlink'))->setText('EDITOR_INSERTLINK')->setIcon('link')->setData(['action' => 'insertLink']),
                'image' => (new \fpcm\view\helper\button('editor-html-buttoninsertimage'))->setText('EDITOR_INSERTPIC')->setIcon('images')->setData(['action' => 'insertPicture']),
                'media' => (new \fpcm\view\helper\button('editor-html-buttoninsertmedia'))->setText('EDITOR_INSERTMEDIA')->setIcon('play')->setData(['action' => 'insertMedia']),
                'frame' => (new \fpcm\view\helper\button('editor-html-buttoninsertframe'))->setText('EDITOR_HTML_BUTTONS_IFRAME')->setIcon('puzzle-piece')->setData(['action' => 'insertIFrame']),
                'pagebreak' => (new \fpcm\view\helper\button('editor-html-buttonreadmore'))->setText('EDITOR_HTML_BUTTONS_PAGEBREAK')->setIcon('percentage')->setData(['action' => 'insertPageBreak']),
                'table' => (new \fpcm\view\helper\button('editor-html-buttontable'))->setText('EDITOR_INSERTTABLE')->setIcon('table')->setData(['action' => 'insertTable']),
                'delim4' => (new \fpcm\view\helper\toolbarSeperator('sep4'))->setClass(' me-1 mb-1'),
                'smileys' => (new \fpcm\view\helper\button('editor-html-buttonsmileys'))->setText('HL_OPTIONS_SMILEYS')->setIcon('smile-beam')->setData(['action' => 'insertSmilies']),
                'drafts' => (new \fpcm\view\helper\button('editor-html-buttondrafts'))->setText('EDITOR_HTML_BUTTONS_ARTICLETPL')->setIcon('file-alt', 'far')->setData(['action' => 'insertDrafts']),
                'symbol' => (new \fpcm\view\helper\button('editor-html-buttonsymbol'))->setText('EDITOR_HTML_BUTTONS_SYMBOL')->setIcon('font')->setData(['action' => 'insertSymbol']),
                'delim5' => (new \fpcm\view\helper\toolbarSeperator('sep5'))->setClass(' me-1 mb-1'),
                'removestyles' => (new \fpcm\view\helper\button('editor-html-buttonremstyles'))->setText('EDITOR_HTML_BUTTONS_REMOVESTYLE')->setIcon('remove-format')->setData(['action' => 'removeTags']),
                'restore' => (new \fpcm\view\helper\button('editor-html-buttonrestore', 'editor-html-buttonrestore'))->setText('EDITOR_AUTOSAVE_RESTORE')->setIcon('undo')->setData(['action' => 'restoreSave'])->setReadonly(true)
            ]
        );

        $ev = $this->events->trigger('editor\initCodemirrorView', $vars);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event editor\initCodemirrorView failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return $vars;
        }

        return $ev->getData();

    }

    /**
     * Arary mit Informationen u. a. für template-Plugin von TinyMCE
     * @see \fpcm\model\abstracts\articleEditor::getTemplateDrafts()
     * @return array
     * @since 3.3
     */
    public function getTemplateDrafts()
    {
        return [];

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
            /*$this->getImageDialog(),
            $this->getMediaDialog(),
            $this->getColorDialog(),
            $this->getQuoteDialog()*/
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
            (new \fpcm\view\helper\select('links[target]'))
                ->setOptions(self::LINK_TARGETS)
                ->setText('EDITOR_LINKTARGET')
                ->setIcon('window-restore')
                ->setLabelTypeFloat()
                ->setBottomSpace('')
        ];

        if (count($this->styles)) {
            $fields[] = (new \fpcm\view\helper\select('links[css]'))
                ->setOptions($this->styles)
                ->setText('EDITOR_CSS_CLASS')
                ->setIcon('paint-roller')
                ->setLabelTypeFloat()
                ->setBottomSpace('');
        }

        $fields[] = (new \fpcm\view\helper\textInput('links[rel]'))
            ->setText('EDITOR_LINKREL')
            ->setIcon('cog')
            ->setLabelTypeFloat()
            ->setBottomSpace('');

        return (new \fpcm\view\helper\dialog('insert-link'))->setFields($fields);
    }

    private function getImageDialog() : \fpcm\view\helper\dialog
    {
        $fields = [
            (new \fpcm\view\helper\textInput('images[path]', 'imagespath'))
                ->setType('url')
                ->setValue('')
                ->setText('EDITOR_IMGPATH')
                ->setIcon('image'),
            (new \fpcm\view\helper\textInput('images[alt]', 'imagesalt'))
                ->setText('EDITOR_IMGALTTXT')
                ->setIcon('keyboard'),
            (new \fpcm\view\helper\select('images[align]', 'imagesalign'))
                ->setOptions(self::LINK_ALIGNS)
                ->setText('EDITOR_IMGALIGN')
                ->setIcon('align-center')
        ];

        if (count($this->styles)) {
            $fields[] = (new \fpcm\view\helper\select('images[css]', 'imagescss'))
                ->setOptions($this->styles)
                ->setText('EDITOR_CSS_CLASS')
                ->setIcon('paint-roller');
        }

        return (new \fpcm\view\helper\dialog('insertimage'))->setFields($fields);
    }

    private function getMediaDialog() : \fpcm\view\helper\dialog
    {

        $playerFormats = $this->language->translate('EDITOR_INSERTMEDIA_FORMATS');

        $fields = [
            (new \fpcm\view\helper\textInput('media[path]', 'mediapath'))
                ->setType('url')
                ->setValue('')
                ->setText('EDITOR_IMGPATH')
                ->setIcon('film '),
            (new \fpcm\view\helper\select('media[format]', 'mediaformat'))
                ->setOptions($playerFormats)
                ->setClass('fpcm-editor-mediaformat')
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED),
            (new \fpcm\view\helper\textInput('media[path]', 'mediapath2'))
                ->setType('url')
                ->setValue('')
                ->setText('EDITOR_IMGPATH_ALT')
                ->setIcon('file-video'),
            (new \fpcm\view\helper\select('media[format2]', 'mediaformat2'))
                ->setOptions($playerFormats)
                ->setClass('fpcm-editor-mediaformat')
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED),
            (new \fpcm\view\helper\textInput('media[poster]', 'mediaposter'))
                ->setType('url')
                ->setValue('')
                ->setText('EDITOR_INSERTMEDIA_POSTER')
                ->setIcon('file-image'),
            (new \fpcm\view\helper\button('insertposterimg', 'insertposterimg'))
                ->setText('HL_FILES_MNG')
                ->setIcon('image')
                ->setIconOnly(),
            (new \fpcm\view\helper\radiobutton('mediatype', 'mediatypea'))
                ->setText('EDITOR_INSERTMEDIA_AUDIO')
                ->setClass('fpcm-editor-mediatype')
                ->setValue('audio')
                ->setSelected(true)
                ->setSwitch(true),
            (new \fpcm\view\helper\radiobutton('mediatype', 'mediatypev'))
                ->setText('EDITOR_INSERTMEDIA_VIDEO')
                ->setClass('fpcm-editor-mediatype')
                ->setValue('video')
                ->setSwitch(true),
            (new \fpcm\view\helper\checkbox('controls', 'controls'))
                ->setText('EDITOR_INSERTMEDIA_CONTROLS')
                ->setValue(1)
                ->setSelected(true)
                ->setSwitch(true),
            (new \fpcm\view\helper\checkbox('autoplay', 'autoplay'))
                ->setText('EDITOR_INSERTMEDIA_AUTOPLAY')
                ->setValue(1)
                ->setSwitch(true),
        ];

        return (new \fpcm\view\helper\dialog('insertmedia'))->setFields($fields);
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
                ->setLabelSize([6])
                ->setClass('h-100'),
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
        ];
   
        return (new \fpcm\view\helper\dialog('insertcolor'))->setFields($fields);
    }
    
    private function getQuoteDialog() : \fpcm\view\helper\dialog
    {
        $fields = [
            (new \fpcm\view\helper\textarea('quote[text]'))
                ->setPlaceholder(true)
                ->setText('EDITOR_HTML_BUTTONS_QUOTE_TEXT')
                ->setIcon('keyboard')
                ->setClass('fpcm ui-textarea-medium ui-textarea-noresize'),
            (new \fpcm\view\helper\textInput('quote[src]'))
                ->setValue('')
                ->setText('TEMPLATE_ARTICLE_SOURCES')
                ->setIcon('external-link-alt')
                ->setSize('lg'),
            (new \fpcm\view\helper\radiobutton('quote[type]', 'quotetype1'))
                ->setText('EDITOR_HTML_BUTTONS_QUOTE_BLOCK')
                ->setClass('fpcm-ui-editor-quotemode')
                ->setValue('blockquote')
                ->setSelected(true)
                ->setSwitch(true),
            (new \fpcm\view\helper\radiobutton('quote[type]', 'quotetype2'))
                ->setText('EDITOR_HTML_BUTTONS_QUOTE_INLINE')
                ->setClass('fpcm-ui-editor-quotemode')
                ->setValue('q')
                ->setSwitch(true)
        ];

        return (new \fpcm\view\helper\dialog('insertquote'))->setFields($fields);
    }

}
