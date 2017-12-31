<?php
    /**
     * Recent articles Dashboard Container
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\editor;

    /**
     * Recent articles dashboard container object
     * 
     * @package fpcm\model\dashboard
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class tinymceEditor extends \fpcm\model\abstracts\articleEditor {

        /**
         * Liefert zu ladender CSS-Dateien für Editor zurück
         * @return array
         */
        public function getCssFiles() {
            return [];
        }

        /**
         * Pfad der Editor-Template-Datei
         * @return string
         */
        public function getEditorTemplate() {
            return \fpcm\classes\baseconfig::$viewsDir.'articles/editors/tinymce.php';
        }

        /**
         * Liefert zu ladender Javascript-Dateien für Editor zurück
         * @return array
         */        
        public function getJsFiles() {

            return array(
                \fpcm\classes\loader::libGetFileUrl('tinymce4', 'tinymce.min.js'),
                'editor.js',
                'editor_tinymce.js',
                'editor_videolinks.js'
            );
        }

        /**
         * Array von Javascript-Variablen, welche in Editor-Template genutzt werden
         * @return array
         */
        public function getJsVars() {

            $editorStyles = array(array('title' => $this->language->translate('GLOBAL_SELECT'), 'value' => ''));

            $cache = new \fpcm\classes\cache('tinymce_plugins');
            if (defined('FPCM_TINYMCE_PLUGINS') && FPCM_TINYMCE_PLUGINS) {
                $pluginFolders = FPCM_TINYMCE_PLUGINS;
                
                $this->notifications->addNotification(new \fpcm\model\theme\notificationItem(
                    'EDITOR_TINYMCE_PLUGIN_OVERRIDE', 
                    'fa fa-plug fa-lg fa-fw'
                ));
                
            }
            elseif ($cache->isExpired()) {

                $path  = dirname(\fpcm\classes\loader::libGetFilePath('tinymce4', 'tinymce.min.js'));            
                $path .= '/plugins/*';

                $pluginFolders = implode(' ', array_map('basename', glob($path, GLOB_ONLYDIR)));
                $cache->write($pluginFolders, $this->config->system_cache_timeout);
            }
            else {
                $pluginFolders = $cache->read();
            }
            
            $params = array(
                'fpcmTinyMceLang'               => $this->config->system_lang,
                'fpcmTinyMceElements'           => 'readmore',
                'fpcmTinyMcePlugins'            => $pluginFolders,
                'fpcmTinyMceToolbar'            => 'formatselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify outdent indent | subscript superscript table toc | bullist numlist | fpcm_readmore hr blockquote | link unlink anchor image media | emoticons charmap insertdatetime template | undo redo removeformat searchreplace fullscreen code restoredraft',
                'fpcmTinyMceCssClasses'         => array_merge($editorStyles, $this->getEditorStyles()),
                'fpcmTinyMceTextpattern'        => $this->getTextPatterns(),
                'fpcmTinyMceDefaultFontsize'    => $this->config->system_editor_fontsize,
                'fpcmTinyMceReadmoreBlockHL'    => $this->language->translate('EDITOR_HTML_BUTTONS_READMORE'),
                'fpcmTinyMceTemplatesList'      => $this->getTemplateDrafts(),
                'fpcmTinyMceAutosavePrefix'     => 'fpcm-editor-as-'.$this->session->getUserId(),
                'fpcmTinyMceFileUpload'         => $this->config->articles_imageedit_persistence ? 1 : 0,
                'fpcmEditorInitFunction'        => 'initTinyMce'
            );
            
            return $this->events->runEvent('editorInitTinymce', $params);
        }

        /**
         * Array von Variablen, welche in Editor-Template genutzt werden
         * @return array
         */
        public function getViewVars() {
            return [];
        }
        
        /**
         * Editor-Styles initialisieren
         * @return array
         */
        protected function getEditorStyles() {
            if (!$this->config->system_editor_css) return [];
            
            $classes = explode(PHP_EOL, $this->config->system_editor_css);
            
            $editorStyles = [];
            foreach ($classes as $class) {
                $class = trim(str_replace(array('.', '{', '}'), '', $class));                
                $editorStyles[] = array('title' => $class, 'value' => $class);
            }
            
            return $this->events->runEvent('editorAddStyles', $editorStyles);
        }
        
        /**
         * Editor-Links initialisieren
         * @return string
         */
        public function getEditorLinks() {
            $links = $this->events->runEvent('editorAddLinks');
            if (!is_array($links) || !count($links)) return [];
            return json_decode(str_replace('label', 'title', json_encode($links)), false);
        }
        
        /**
         * Dateiliste initialisieren
         * @return array
         */
        public function getFileList() {
            $data = [];            
            foreach ($this->fileList->getDatabaseList() as $image) {
                $data[] = array('title' => $image->getFilename(), 'value' => $image->getImageUrl());
            }

            $res = $this->events->runEvent('editorGetFileList', array('label' => 'title', 'files' => $data));
            
            return isset($res['files']) && count($res['files']) ? $res['files'] : [];
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
                    continue;;
                }
                    
                $ret[] = array(
                    "title" => $basename,
                    "url"   => \fpcm\classes\baseconfig::$rootPath.\fpcm\model\files\ops::removeBaseDir($file)
                );
                
            }
            
            return $ret;
        }

        /**
         * Array von Sprachvariablen für Nutzung in Javascript
         * @see \fpcm\model\abstracts\articleEditor
         * @return array
         * @since FPCM 3.3
         */
        public function getJsLangVars() {
            return [];
        }

    }