<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\traits\articles;
    
    /**
     * Artikelliste trait
     * 
     * @package fpcm\controller\traits\articles\editor
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.6
     */    
    trait editor {

        /**
         * Editor-Plugin ermitteln
         * @return \fpcm\model\abstracts\articleEditor
         */
        public function getEditorPlugin() {
            
            $eventResult = $this->events->runEvent('articleReplaceEditorPlugin');
            if (is_a($eventResult, '\fpcm\model\abstracts\articleEditor')) {
                return $eventResult;
            }

            if ($this->config->system_editor) {
                return new \fpcm\model\editor\htmlEditor();
            }

            return new \fpcm\model\editor\tinymceEditor();
        }

    }