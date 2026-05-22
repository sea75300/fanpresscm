<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\editor;

/**
 * TinyMCE based editor plugin
 *
 * @package fpcm\components\editor
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class tinymceEditor5 extends articleEditor {

    /**
     * Files list label name
     * @since 4.5
     */
    const FILELIST_LABEL = 'title';

    /**
     * Files list value name
     * @since 4.5
     */
    const FILELIST_VALUE = 'value';

    /**
     * Liefert zu ladender CSS-Dateien für Editor zurück
     * @return array
     */
    public function getCssFiles() : array
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
     * Pfad der Kommentar-Editor-Template-Datei
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
    public function getJsFiles() : array
    {
        return [\fpcm\classes\loader::libGetFileUrl('tinymce5/tinymce.min.js'), 'editor/tinymce.js', 'editor/filemanager.js'];
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
                'EDITOR_TINYMCE_PLUGIN_OVERRIDE',
                'fa fa-plug fa-lg fa-fw'
            ));
        } elseif ($this->cache->isExpired('tinymce_plugins')) {

            $path = dirname(\fpcm\classes\loader::libGetFilePath('tinymce5/tinymce.min.js'));
            $path .= '/plugins/*';

            $pluginFolders = array_map('basename', glob($path, GLOB_ONLYDIR));
            $this->cache->write('tinymce_plugins', $pluginFolders, $this->config->system_cache_timeout);
        } else {
            $pluginFolders = $this->cache->read('tinymce_plugins');
        }

        $cssClasses = array_merge($editorStyles, $this->getEditorStyles());

        $cfg = [
            'editorConfig' => new conf\tinymceEditor5(
                $this->config,
                $pluginFolders,
                $cssClasses,
                $this->getTextPatterns(),
                $this->getTemplateDrafts(),
                $this->session->getUserId()
            ),
            'editorDefaultFontsize' => $this->config->system_editor_fontsize,
            'uploadFileRoot' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_UPLOADS, ''),
            'galleryThumbStr' => \fpcm\model\pubtemplates\article::GALLERY_TAG_THUMB
        ];

        $ev = $this->events->trigger('editor\initTinymce', $cfg);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event editor\initTinymce failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return $cfg;
        }

        return $ev->getData();


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
    protected function getEditorStyles() : array
    {
        $classes = parent::getEditorStyles();

        if (!is_array($classes) || !count($classes)) {
            return [];
        }

        $return = array_map(function ($class) {
            return [
                'title' => $class,
                'value' => $class
            ];
        }, $classes);

        return array_values($return);
    }

    /**
     * Editor-Links initialisieren
     * @return array
     */
    public function getEditorLinks(string $labelString = 'title') : array
    {
        $links = parent::getEditorLinks();
        if (!is_array($links) || !count($links)) {
            return [];
        }

        $return = array_map(function ($item) use ($labelString) {
            return [
                $labelString => $item['label'],
                'value' => $item['value']
            ];
        }, $links);

        return array_values($return);
    }

    /**
     * Arary mit Informationen u. a. für template-Plugin von TinyMCE
     * @see \fpcm\model\abstracts\articleEditor::getTemplateDrafts()
     * @return array
     * @since 3.3
     */
    public function getTemplateDrafts() : array
    {
        $tpFfiles = parent::getTemplateDrafts();
        if (!is_array($tpFfiles) || !count($tpFfiles)) {
            return [];
        }

        $return = array_map(function ($basename) {
            return [
                'title' => $basename,
                'description' => $basename,
                'url' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_DRAFTS, $basename)
            ];
        }, $tpFfiles);

        return array_values($return);
    }

    /**
     * Array von Sprachvariablen für Nutzung in Javascript
     * @see \fpcm\model\abstracts\articleEditor
     * @return array
     * @since 3.3
     */
    public function getJsLangVars() : array
    {
        return ['EDITOR_INSERTSMILEY'];
    }

}
