<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components;

/**
 * Component loader
 * 
 * @package fpcm\drivers\mysql
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class components {

    public static function getArticleEditor()
    {
        $class = \fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_editor;
        if (!class_exists($class)) {
            $view = new \fpcm\view\error('Error loading article editor component '.$class);
            $view->render();
        }
        
        return new $class();
    }

    public static function getArticleEditors()
    {
        return array_map('base64_encode', \fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('editor\getEditors', [
            'SYSTEM_OPTIONS_NEWS_EDITOR_STD' => '\fpcm\components\editor\tinymceEditor',
            'SYSTEM_OPTIONS_NEWS_EDITOR_CLASSIC' => '\fpcm\components\editor\htmlEditor'
        ]));
    }
    
}
