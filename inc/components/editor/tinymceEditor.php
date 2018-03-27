<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\editor;

/**
 * TinyMCE based editor plugin
 * 
 * @package fpcm\components\editor
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class tinymceEditor extends articleEditor {

    /**
     * Liefert zu ladender CSS-Dateien für Editor zurück
     * @return array
     */
    public function getCssFiles()
    {
        return [];
    }

    /**
     * Pfad der Editor-Template-Datei
     * @return string
     */
    public function getEditorTemplate()
    {
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'articles/editors/tinymce.php');
    }

    /**
     * 
     * @return string
     */
    public function getCommentEditorTemplate()
    {
        return \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'comments/editors/tinymce.php');        
    }

    /**
     * Liefert zu ladender Javascript-Dateien für Editor zurück
     * @return array
     */
    public function getJsFiles()
    {
        return [\fpcm\classes\loader::libGetFileUrl('tinymce4/tinymce.min.js'), 'editor_tinymce.js'];
    }

    /**
     * Array von Javascript-Variablen, welche in Editor-Template genutzt werden
     * @return array
     */
    public function getJsVars()
    {
        $editorStyles = array(array('title' => $this->language->translate('GLOBAL_SELECT'), 'value' => ''));

        if (defined('FPCM_TINYMCE_PLUGINS') && FPCM_TINYMCE_PLUGINS) {
            $pluginFolders = FPCM_TINYMCE_PLUGINS;

            $this->notifications->addNotification(new \fpcm\model\theme\notificationItem(
                    'EDITOR_TINYMCE_PLUGIN_OVERRIDE', 'fa fa-plug fa-lg fa-fw'
            ));
        } elseif ($this->cache->isExpired('tinymce_plugins')) {

            $path = dirname(\fpcm\classes\loader::libGetFilePath('tinymce4/tinymce.min.js'));
            $path .= '/plugins/*';

            $pluginFolders = implode(' ', array_map('basename', glob($path, GLOB_ONLYDIR)));
            $this->cache->write('tinymce_plugins', $pluginFolders, $this->config->system_cache_timeout);
        } else {
            $pluginFolders = $this->cache->read('tinymce_plugins');
        }

        $cssClasses = array_merge($editorStyles, $this->getEditorStyles());

        return $this->events->runEvent('editorInitTinymce', [
                    'editorConfig' => [
                        'language' => $this->config->system_lang,
                        'plugins' => $pluginFolders,
                        'custom_elements' => 'readmore',
                        'toolbar' => 'formatselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify outdent indent | subscript superscript table toc | bullist numlist | fpcm_readmore hr blockquote | link unlink anchor image media | emoticons charmap insertdatetime template | undo redo removeformat searchreplace fullscreen code restoredraft',
                        'link_class_list' => $cssClasses,
                        'image_class_list' => $cssClasses,
                        'link_list' => \fpcm\classes\tools::getFullControllerLink('ajax/autocomplete', ['src' => 'editorlinks']),
                        'image_list' => \fpcm\classes\tools::getFullControllerLink('ajax/autocomplete', ['src' => 'editorfiles']),
                        'textpattern_patterns' => $this->getTextPatterns(),
                        'templates' => $this->getTemplateDrafts(),
                        'autosave_prefix' => 'fpcm-editor-as-' . $this->session->getUserId(),
                        'images_upload_url' => $this->config->articles_imageedit_persistence ? \fpcm\classes\tools::getFullControllerLink('ajax/editor/imgupload') : false,
                        'automatic_uploads' => $this->config->articles_imageedit_persistence ? 1 : 0,
                        'autoresize_min_height' => 500
                    ],
                    'editorDefaultFontsize' => $this->config->system_editor_fontsize,
                    'editorInitFunction' => 'initTinyMce'
        ]);
    }

    /**
     * Array von Variablen, welche in Editor-Template genutzt werden
     * @return array
     */
    public function getViewVars()
    {
        return [];
    }

    /**
     * Editor-Styles initialisieren
     * @return array
     */
    protected function getEditorStyles()
    {
        if (!$this->config->system_editor_css) {
            return [];
        }

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
    public function getEditorLinks()
    {
        $links = $this->events->runEvent('editorAddLinks');
        if (!is_array($links) || !count($links)) {
            return [];
        }

        return json_decode(str_replace('label', 'title', json_encode($links)), false);
    }

    /**
     * Dateiliste initialisieren
     * @return array
     */
    public function getFileList()
    {
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
    public function getTemplateDrafts()
    {
        $templatefilelist = new \fpcm\model\files\templatefilelist();

        $ret = [];
        foreach ($templatefilelist->getFolderList() as $file) {

            $basename = basename($file);

            if ($basename === 'index.html') {
                continue;
                ;
            }

            $ret[] = array(
                "title" => $basename,
                "url" => \fpcm\classes\dirs::getRootUrl(\fpcm\model\files\ops::removeBaseDir($file))
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
    public function getJsLangVars()
    {
        return ['EDITOR_HTML_BUTTONS_READMORE'];
    }

}
