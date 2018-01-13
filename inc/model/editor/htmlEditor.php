<?php
    /**
     * Recent articles Dashboard Container
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\editor;

    /**
     * Recent articles dashboard container object
     * 
     * @package fpcm\model\dashboard
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class htmlEditor extends \fpcm\model\abstracts\articleEditor {

        /**
         * Liefert zu ladender CSS-Dateien für Editor zurück
         * @return array
         */
        public function getCssFiles()
        {
            return [
                \fpcm\classes\dirs::getIncDirPath('codemirror/lib/codemirror.css'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/theme/fpcm.css'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/hint/show-hint.css'),
            ];
        }

        /**
         * Pfad der Editor-Template-Datei
         * @return string
         */
        public function getEditorTemplate()
        {
            return \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_VIEWS, 'articles/editors/html.php');
        }

        /**
         * Liefert zu ladender Javascript-Dateien für Editor zurück
         * @return array
         */ 
        public function getJsFiles()
        {

            return [
                \fpcm\classes\dirs::getIncDirPath('codemirror/lib/codemirror.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/selection/active-line.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/edit/matchbrackets.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/edit/matchtags.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/edit/closetag.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/old/xml-fold.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/hint/show-hint.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/hint/xml-hint.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/hint/html-hint.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/runmode/runmode.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/addon/runmode/colorize.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/mode/xml/xml.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/mode/javascript/javascript.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/mode/css/css.js'),
                \fpcm\classes\dirs::getIncDirPath('codemirror/mode/htmlmixed/htmlmixed.js'),
                \fpcm\classes\loader::libGetFileUrl('leela-colorpicker/leela.colorpicker-1.0.2.jquery.min.js'),
                'editor.js',
                'editor_codemirror.js',
                'editor_videolinks.js'
            ];
        }

        /**
         * Array von Javascript-Variablen, welche in Editor-Template genutzt werden
         * @return array
         */
        public function getJsVars()
        {
            return [
                'cmConfig'          => [
                    'colors'        => [
                        '#000000','#993300','#333300','#003300','#003366','#00007f','#333398','#333333',
                        '#800000','#ff6600','#808000','#007f00','#007171','#0000e8','#5d5d8b','#6c6c6c',
                        '#f00000','#e28800','#8ebe00','#2f8e5f','#30bfbf','#3060f1','#770077','#8d8d8d',                
                        '#f100f1','#f0c000','#eeee00','#00f200','#00efef','#00beee','#8d2f5e','#b5b5b5',
                        '#ed8ebe','#efbf8f','#e8e88b','#bbeabb','#bcebeb','#89b6e4','#b88ae6','#ffffff'
                    ],
                    'autosavePref'  => 'fpcm-editor-as-'.$this->session->getUserId().'draft',                    
                ]
            ]
        }

        /**
         * Array von Sprachvariablen für Nutzung in Javascript
         * @see \fpcm\model\abstracts\articleEditor
         * @return array
         * @since FPCM 3.3
         */
        public function getJsLangVars()
        {
            return [
                'GLOBAL_INSERT', 'EDITOR_INSERTPIC', 'EDITOR_INSERTLINK', 'EDITOR_INSERTTABLE',
                'EDITOR_INSERTCOLOR', 'EDITOR_INSERTMEDIA', 'EDITOR_INSERTSMILEY', 'EDITOR_INSERTSYMBOL',
                'EDITOR_HTML_BUTTONS_ARTICLETPL', 'EDITOR_HTML_BUTTONS_LISTUL', 'EDITOR_HTML_BUTTONS_LISTOL'
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
                    'left'      => 'left',
                    'center'    => 'center',
                    'right'     => 'right'
                ),
                'targets' => array(
                    '_blank'  => '_blank',
                    '_top'    => '_top',
                    '_self'   => '_self',
                    '_parent' => '_parent'
                ),
                'editorStyles' => $editorStyles,
                'cssClasses'   => $editorStyles,
                'extraButtons' => array(
                    array('title' => '', 'id' => '', 'class' => '', 'htmltag' => '', 'icon' => '')
                ),
                'editorFontsizes'   => array(8,9,10,11,12,14,16,18,20,24),
                'editorParagraphs'  => array(
                    $this->language->translate('EDITOR_PARAGRAPH')               => 'p',
                    $this->language->translate('EDITOR_PARAGRAPH_HEADLINE').' 1' => 'h1',
                    $this->language->translate('EDITOR_PARAGRAPH_HEADLINE').' 2' => 'h2',
                    $this->language->translate('EDITOR_PARAGRAPH_HEADLINE').' 3' => 'h3',
                    $this->language->translate('EDITOR_PARAGRAPH_HEADLINE').' 4' => 'h4',
                    $this->language->translate('EDITOR_PARAGRAPH_HEADLINE').' 5' => 'h5',
                    $this->language->translate('EDITOR_PARAGRAPH_HEADLINE').' 6' => 'h6',
                    $this->language->translate('EDITOR_PRE')                     => 'pre',
                    $this->language->translate('EDITOR_CODE')                    => 'code',
                ),
                'editorDefaultFontsize' => $this->config->system_editor_fontsize,
                'editorTemplatesList'   => $this->getTemplateDrafts()
            );

            $vars = $this->events->runEvent('editorInitHtml', $vars);
            array_shift($vars['extraButtons']);
            
            return $vars;
        }

        /**
         * Arary mit Informationen u. a. für template-Plugin von TinyMCE
         * @see \fpcm\model\abstracts\articleEditor::getTemplateDrafts()
         * @return array
         * @since FPCM 3.3
         */
        public function getTemplateDrafts() {

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