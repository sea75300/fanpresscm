<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

require_once __DIR__.'/UploadHandler.php';

/**
 * FanPress CM 4.x, Upload handler for jQuery Upload
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2018-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\http
 */
class fpcmUploadHandler extends UploadHandler
{
    protected function basename($filepath, $suffix = '')
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
