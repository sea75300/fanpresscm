<?php

require_once __DIR__.'/UploadHandler.php';

/**
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

/**
 * FanPress CM 4.x, Upload handler for jQuery Upload
 * @package fpcm\classes\baseconfig
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2018, Stefan Seehafer
 */

class fpcmUploadHandler extends UploadHandler
{
    protected function basename($filepath, $suffix = null)
    {
        return fpcm\classes\tools::escapeFileName(parent::basename($filepath, $suffix));
    }
    
    protected function get_query_param($id) {
        return $_GET[$id] ?? null;
    }
    
    protected function get_server_var($id) {
        return $_SERVER[$id] ?? null;
    }

    protected function get_post_param($id) {
        return $_POST[$id] ?? null;
    }

}
