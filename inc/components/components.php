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

    /**
     * Return selected article editor object
     * @return editor\articleEditor
     */
    public static function getArticleEditor() : object
    {
        $class = str_replace('\\\\', '\\', \fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_editor);
        if (!class_exists($class) || !is_subclass_of($class, 'fpcm\components\editor\articleEditor')) {
            trigger_error('Error loading article editor component '.$class);
            return false;
        }
        
        return \fpcm\classes\loader::getObject($class);
    }

    /**
     * Returns lsit of available article editor objects
     * @return array
     */
    public static function getArticleEditors() : array
    {
        $list = [
            'SYSTEM_OPTIONS_NEWS_EDITOR_TINYMCE5' => '\fpcm\components\editor\tinymceEditor5',
            'SYSTEM_OPTIONS_NEWS_EDITOR_CLASSIC' => '\fpcm\components\editor\htmlEditor'
        ];
         
        return array_map('base64_encode', \fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('editor\getEditors', $list));
    }

    /**
     * Return list of mass edit fields in view
     * @param array $masseditFields
     * @return bool
     */
    public static function getMassEditFields(array $masseditFields = [])
    {
        include \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'components/massedit.php');
        return true;
    }

    /**
     * Returns auth provider object for login process
     * @return \fpcm\model\abstracts\authProvider
     */
    public static function getAuthProvider() : object
    {
        $class = \fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('getAuthProvider');
        if (class_exists($class) && is_subclass_of($class, 'fpcm\model\abstracts\authProvider')) {
            return \fpcm\classes\loader::getObject($class);
        }

        return \fpcm\classes\loader::getObject('\fpcm\model\auth\htmlLogin');
    }
    
    /**
     * Returns captcha object in view and captcha-check
     * @return \fpcm\model\abstracts\spamCaptcha
     */
    public static function getChatptchaProvider() : object
    {
        $class = \fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('pub\replaceSpamCaptcha');
        if (class_exists($class) && is_subclass_of($class, '\fpcm\model\abstracts\spamCaptcha')) {
            return \fpcm\classes\loader::getObject($class);
        }

        return \fpcm\classes\loader::getObject('\fpcm\model\captchas\fpcmDefault');
    }

    /**
     * Returns list of file manager view modes
     * @return array
     */
    public static function getFilemanagerViews() : array
    {
        return [
            'SYSTEM_OPTIONS_FILEMANAGER_VIEWCARDS' => 'cards',
            'SYSTEM_OPTIONS_FILEMANAGER_VIEWLIST' => 'list'
        ];
    }

    /**
     * Return jQuery libary path, generic instance
     * @return fileupload\uploader
     * @since FPCM 4.5
     */
    public static function getFileUploader() : object
    {
        return new fileupload\jqupload();
    }

    /**
     * Return jQuery libary path, generic instance
     * @return string
     * @since FPCM 4.5
     */
    public static function getjQuery() : string
    {
        return \fpcm\classes\dirs::getLibUrl('jquery/jquery-3.5.1.min.js');
    }

}
