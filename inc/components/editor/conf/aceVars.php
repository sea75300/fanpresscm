<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\editor\conf;

/**
 * TinyMCE 5 based editor plugin config class
 *
 * @package fpcm\components\editor\conf
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
class aceVars {

    /*use \fpcm\model\traits\jsonSerializeReturnObject;*/

    private $toolbarTpl = 'components/editor/ace_toolbar.php';

    private $editorStyles = [];

    private $editorFontsizes = [];

    private $editorParagraphs = [];

    private $editorButtons = [];

    public function __construct(array $editorStyles)
    {
        $this->editorStyles = array_map(function ($val) {
            return (new \fpcm\view\helper\dropdownItem('style-'.md5($val)))
                ->setText($val)
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => $val, 'action' => 'insertStyle'])
                ->setValue(md5($val));
        }, $editorStyles);

        $this->editorFontsizes = [
            (new \fpcm\view\helper\dropdownItem('fs-8pt'))
                ->setText('8pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '8', 'action' => 'insertFontsize'])
                ->setValue('8'),
            (new \fpcm\view\helper\dropdownItem('fs-9pt'))
                ->setText('9pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '9', 'action' => 'insertFontsize'])
                ->setValue('9'),
            (new \fpcm\view\helper\dropdownItem('fs-10pt'))
                ->setText('10pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '10', 'action' => 'insertFontsize'])
                ->setValue('10'),
            (new \fpcm\view\helper\dropdownItem('fs-11pt'))
                ->setText('11pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '11pt', 'action' => 'insertFontsize'])
                ->setValue('11'),
            (new \fpcm\view\helper\dropdownItem('fs-12pt'))
                ->setText('12pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '12', 'action' => 'insertFontsize'])
                ->setValue('12'),
            (new \fpcm\view\helper\dropdownItem('fs-13pt'))
                ->setText('13pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '13', 'action' => 'insertFontsize'])
                ->setValue('13'),
            (new \fpcm\view\helper\dropdownItem('fs-14pt'))
                ->setText('14pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '14', 'action' => 'insertFontsize'])
                ->setValue('14'),
            (new \fpcm\view\helper\dropdownItem('fs-16pt'))
                ->setText('16pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '16', 'action' => 'insertFontsize'])
                ->setValue('16'),
            (new \fpcm\view\helper\dropdownItem('fs-18pt'))
                ->setText('18pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '18', 'action' => 'insertFontsize'])
                ->setValue('18'),
            (new \fpcm\view\helper\dropdownItem('fs-20pt'))
                ->setText('20pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '20', 'action' => 'insertFontsize'])
                ->setValue('20'),
            (new \fpcm\view\helper\dropdownItem('fs-24pt'))
                ->setText('24pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '24', 'action' => 'insertFontsize'])
                ->setValue('24'),
            (new \fpcm\view\helper\dropdownItem('fs-32pt'))
                ->setText('32pt')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => '32', 'action' => 'insertFontsize'])
                ->setValue('32'),
        ];

        $this->editorParagraphs = [
            (new \fpcm\view\helper\dropdownItem('para-p'))
                ->setText('EDITOR_PARAGRAPH')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => 'p'])
                ->setValue('p'),
            (new \fpcm\view\helper\dropdownItem('para-h1'))
                ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 1])
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => 'h1'])
                ->setValue('h1'),
            (new \fpcm\view\helper\dropdownItem('para-h2'))
                ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 2])
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => 'h2'])
                ->setValue('h2'),
            (new \fpcm\view\helper\dropdownItem('para-h3'))
                ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 3])
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => 'h3'])
                ->setValue('h3'),
            (new \fpcm\view\helper\dropdownItem('para-h4'))
                ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 4])
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => 'h4'])
                ->setValue('h4'),
            (new \fpcm\view\helper\dropdownItem('para-h5'))
                ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 5])
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => 'h5'])
                ->setValue('h5'),
            (new \fpcm\view\helper\dropdownItem('para-h6'))
                ->setText('EDITOR_PARAGRAPH_HEADLINE', ['num' => 6])
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => 'h6'])
                ->setValue('h6'),
            (new \fpcm\view\helper\dropdownItem('para-pre'))
                ->setText('EDITOR_PRE')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => 'pre'])
                ->setValue('pre'),
            (new \fpcm\view\helper\dropdownItem('para-code'))
                ->setText('code')
                ->setClass('fpcm-editor-ace-item')
                ->setData(['htmltag' => 'code'])
                ->setValue('code'),
        ];

        $this->editorButtons = [
            'bold' => (new \fpcm\view\helper\button('-ace-bold'))->setText('EDITOR_HTML_BUTTONS_BOLD')->setIcon('bold')->setData(['htmltag' => 'b']),
            'italic' => (new \fpcm\view\helper\button('-ace-italic'))->setText('EDITOR_HTML_BUTTONS_ITALIC')->setIcon('italic')->setData(['htmltag' => 'i']),
            'underline' => (new \fpcm\view\helper\button('-ace-underline'))->setText('EDITOR_HTML_BUTTONS_UNDERLINE')->setIcon('underline')->setData(['htmltag' => 'u']),
            'strike' => (new \fpcm\view\helper\button('-ace-strike'))->setText('EDITOR_HTML_BUTTONS_STRIKE')->setIcon('strikethrough')->setData(['htmltag' => 's']),
            'delim1' => (new \fpcm\view\helper\toolbarSeperator('sep1'))->setClass(' me-1 mb-1'),
            'color' => (new \fpcm\view\helper\button('-ace-insertcolor'))->setText('EDITOR_INSERTCOLOR')->setIcon('palette')->setData(['action' => 'insertColor']),
            'sup' => (new \fpcm\view\helper\button('-ace-sup'))->setText('EDITOR_HTML_BUTTONS_SUP')->setIcon('superscript')->setData(['htmltag' => 'sup']),
            'sub' => (new \fpcm\view\helper\button('-ace-sub'))->setText('EDITOR_HTML_BUTTONS_SUB')->setIcon('subscript')->setData(['htmltag' => 'sub']),
            'aleft' => (new \fpcm\view\helper\button('-ace-aleft'))->setText('EDITOR_HTML_BUTTONS_ALEFT')->setIcon('align-left')->setData(['htmltag' => 'left', 'action' => 'insertAlignTags']),
            'acenter' => (new \fpcm\view\helper\button('-ace-acenter'))->setText('EDITOR_HTML_BUTTONS_ACENTER')->setIcon('align-center')->setData(['htmltag' => 'center', 'action' => 'insertAlignTags']),
            'aright' => (new \fpcm\view\helper\button('-ace-aright'))->setText('EDITOR_HTML_BUTTONS_ARIGHT')->setIcon('align-right')->setData(['htmltag' => 'right', 'action' => 'insertAlignTags']),
            'ajustify' => (new \fpcm\view\helper\button('-ace-ajustify'))->setText('EDITOR_HTML_BUTTONS_AJUSTIFY')->setIcon('align-justify')->setData(['htmltag' => 'justify', 'action' => 'insertAlignTags']),
            'delim2' => (new \fpcm\view\helper\toolbarSeperator('sep2'))->setClass(' me-1 mb-1'),
            'listul' => (new \fpcm\view\helper\button('-ace-insertlist'))->setText('EDITOR_HTML_BUTTONS_LISTUL')->setIcon('list-ul')->setData(['htmltag' => 'ul', 'action' => 'insertList']),
            'listol' => (new \fpcm\view\helper\button('-ace-insertlistnum'))->setText('EDITOR_HTML_BUTTONS_LISTOL')->setIcon('list-ol')->setData(['htmltag' => 'ol', 'action' => 'insertList']),
            'delim3' => (new \fpcm\view\helper\toolbarSeperator('sep3'))->setClass(' me-1 mb-1'),
            'quote' => (new \fpcm\view\helper\button('-ace-quote'))->setText('EDITOR_HTML_BUTTONS_QUOTE')->setIcon('quote-left')->setData(['action' => 'insertQuote']),
            'link' => (new \fpcm\view\helper\button('-ace-insertlink'))->setText('EDITOR_INSERTLINK')->setIcon('link')->setData(['action' => 'insertLink']),
            'image' => (new \fpcm\view\helper\button('-ace-insertimage'))->setText('EDITOR_INSERTPIC')->setIcon('images')->setData(['action' => 'insertPicture']),
            'media' => (new \fpcm\view\helper\button('-ace-insertmedia'))->setText('EDITOR_INSERTMEDIA')->setIcon('play')->setData(['action' => 'insertMedia']),
            'frame' => (new \fpcm\view\helper\button('-ace-insertframe'))->setText('EDITOR_HTML_BUTTONS_IFRAME')->setIcon('file-half-dashed')->setData(['action' => 'insertIFrame']),
            'pagebreak' => (new \fpcm\view\helper\button('-ace-readmore'))->setText('EDITOR_HTML_BUTTONS_PAGEBREAK')->setIcon('percentage')->setData(['action' => 'insertPageBreak']),
            'table' => (new \fpcm\view\helper\button('-ace-table'))->setText('EDITOR_INSERTTABLE')->setIcon('table')->setData(['action' => 'insertTable']),
            'delim4' => (new \fpcm\view\helper\toolbarSeperator('sep4'))->setClass(' me-1 mb-1'),
            'smileys' => (new \fpcm\view\helper\button('-ace-smileys'))->setText('HL_OPTIONS_SMILEYS')->setIcon('smile-beam')->setData(['action' => 'insertSmilies']),
            'drafts' => (new \fpcm\view\helper\button('-ace-drafts'))->setText('EDITOR_HTML_BUTTONS_ARTICLETPL')->setIcon('file-alt', 'far')->setData(['action' => 'insertDrafts']),
            'symbol' => (new \fpcm\view\helper\button('-ace-symbol'))->setText('EDITOR_HTML_BUTTONS_SYMBOL')->setIcon('font')->setData(['action' => 'insertSymbol']),
            'delim5' => (new \fpcm\view\helper\toolbarSeperator('sep5'))->setClass(' me-1 mb-1'),
            'removestyles' => (new \fpcm\view\helper\button('-ace-remstyles'))->setText('EDITOR_HTML_BUTTONS_REMOVESTYLE')->setIcon('remove-format')->setData(['action' => 'removeTags']),
            'restore' => (new \fpcm\view\helper\button('-ace-restore'))->setText('EDITOR_AUTOSAVE_RESTORE')->setIcon('robot')->setData(['action' => 'restoreSave'])->setReadonly(true),
            'undo' => (new \fpcm\view\helper\button('-ace-undo'))->setText('EDITOR_HTML_BUTTONS_UNDO')->setIcon('undo')->setData(['action' => 'undo'])->setReadonly(true),
            'redo' => (new \fpcm\view\helper\button('-ace-redo'))->setText('EDITOR_HTML_BUTTONS_REDO')->setIcon('redo')->setData(['action' => 'redo'])->setReadonly(true)
        ];
    }

    public function toArray() : array
    {
        return get_object_vars($this);
    }

    /**
     * Prepare buttons for comments
     * @return void
     */
    public function prepareComments() : void
    {
        $this->editorButtons['frame']->setReturned(true);
        unset($this->editorButtons['frame']);
        $this->editorButtons['pagebreak']->setReturned(true);
        unset($this->editorButtons['pagebreak']);
        $this->editorButtons['drafts']->setReturned(true);
        unset($this->editorButtons['drafts']);
        $this->editorButtons['restore']->setReturned(true);
        unset($this->editorButtons['restore']);
    }

    /**
     * Prepare buttons for comments
     * @return void
     */
    public function prepareDrafts() : void
    {
        $this->editorButtons['quote']->setReturned(true);
        unset($this->editorButtons['quote']);
        $this->editorButtons['pagebreak']->setReturned(true);
        unset($this->editorButtons['pagebreak']);
    }

}
