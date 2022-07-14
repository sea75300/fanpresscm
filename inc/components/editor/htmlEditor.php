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
class htmlEditor extends articleEditor {
    
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
     * Liefert zu ladender CSS-Dateien für Editor zurück
     * @return array
     */
    public function getCssFiles()
    {
        return [
            \fpcm\classes\dirs::getLibUrl('codemirror/lib/codemirror.css'),
            \fpcm\classes\dirs::getLibUrl('codemirror/theme/fpcm.css'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/hint/show-hint.css')
        ];
    }

    /**
     * Pfad der Editor-Template-Datei
     * @return string
     */
    public function getEditorTemplate()
    {
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'articles/editors/html.php');
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
            \fpcm\classes\dirs::getLibUrl('codemirror/lib/codemirror.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/selection/active-line.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/edit/matchbrackets.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/edit/matchtags.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/edit/closetag.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/fold/xml-fold.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/hint/show-hint.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/hint/xml-hint.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/hint/html-hint.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/runmode/runmode.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/addon/runmode/colorize.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/mode/yaml/yaml.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/mode/xml/xml.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/mode/javascript/javascript.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/mode/css/css.js'),
            \fpcm\classes\dirs::getLibUrl('codemirror/mode/htmlmixed/htmlmixed.js'),
            \fpcm\classes\dirs::getLibUrl('nkorg/jscharmap/charmap.js'),
            'editor/editor_filemanager.js',
            'editor/editor_codemirror.js'
        ];
    }

    /**
     * Array von Javascript-Variablen, welche in Editor-Template genutzt werden
     * @return array
     */
    public function getJsVars()
    {
        return $this->events->trigger('editor\initCodemirrorJs', [
            'editorConfig' => [
                'colors' => [
                    '#000000', '#993300', '#333300', '#003300', '#003366', '#00007f', '#333398', '#333333',
                    '#800000', '#ff6600', '#808000', '#007f00', '#007171', '#0000e8', '#5d5d8b', '#6c6c6c',
                    '#f00000', '#e28800', '#8ebe00', '#2f8e5f', '#30bfbf', '#3060f1', '#770077', '#8d8d8d',
                    '#f100f1', '#f0c000', '#eeee00', '#00f200', '#00efef', '#00beee', '#8d2f5e', '#b5b5b5',
                    '#ed8ebe', '#efbf8f', '#e8e88b', '#bbeabb', '#bcebeb', '#89b6e4', '#b88ae6', '#ffffff'
                ],
                'autosavePref' => 'fpcm-editor-as-' . $this->session->getUserId() . 'draft',
                'pageBreakVar' => \fpcm\model\pubtemplates\article::PAGEBREAK_TAG
            ],
            'editorInitFunction' => 'initCodeMirror'
        ]);
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
            'EDITOR_HTML_BUTTONS_IFRAME'
        ];
    }

    /**
     * Array von Variablen, welche in Editor-Template genutzt werden
     * @return array
     */
    public function getViewVars()
    {
        $editorStyles = $this->getEditorStyles();

        $vars = array(
            'aligns' => array(
                'left' => 'left',
                'center' => 'center',
                'right' => 'right'
            ),
            'targets' => array(
                '_blank' => '_blank',
                '_top' => '_top',
                '_self' => '_self',
                '_parent' => '_parent'
            ),
            'editorStyles' => array_map(function ($val) {
                    return (new \fpcm\view\helper\dropdownItem('style-'.md5($val)))
                        ->setText($val)
                        ->setClass('fpcm-editor-html-click')
                        ->setData(['htmltag' => $val, 'action' => 'insertStyle'])
                        ->setValue(md5($val));
                },
                $editorStyles
            ),
            'cssClasses' => $editorStyles,
            'playerFormats' => $this->language->translate('EDITOR_INSERTMEDIA_FORMATS'),
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

        return $this->events->trigger('editor\initCodemirrorView', $vars);
    }

    /**
     * Arary mit Informationen u. a. für template-Plugin von TinyMCE
     * @see \fpcm\model\abstracts\articleEditor::getTemplateDrafts()
     * @return array
     * @since 3.3
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

}
