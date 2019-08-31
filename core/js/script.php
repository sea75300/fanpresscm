<?php
/**
 * Combined JavaScript file
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

require_once dirname(dirname(__DIR__)).'/inc/common.php';

$unique = preg_replace('/([^a-z0-9]*)/', '', fpcm\classes\http::getOnly('uq', [ fpcm\classes\http::FILTER_TRIM ]));
$cacheName = fpcm\view\view::JS_FILES_CACHE.'data'.$unique;

/* @var $cache fpcm\classes\cache */
$cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
$data = $cache->read($cacheName);

if (!is_array($data) || \fpcm\classes\baseconfig::installerEnabled() || FPCM_DEBUG) {
    $data  = [
        'content' => '',
        'filesize' => 0
    ];
}

if ($cache->isExpired($cacheName) || \fpcm\classes\baseconfig::installerEnabled() || FPCM_DEBUG) {
    
    $jsFiles = $cache->read(fpcm\view\view::JS_FILES_CACHE.$unique);
    if (!is_array($jsFiles)) {
        $jsFiles = [];
    }

    $jsFilesDefault = [
        __DIR__.DIRECTORY_SEPARATOR.'ajax.js',
        __DIR__.DIRECTORY_SEPARATOR.'ui.js',
        __DIR__.DIRECTORY_SEPARATOR.'ui_navigation.js',
        __DIR__.DIRECTORY_SEPARATOR.'notifications.js',
        __DIR__.DIRECTORY_SEPARATOR.'system.js'
    ];

    foreach (array_merge($jsFilesDefault, $jsFiles) as $jsFile) {

        $fileContent = file_get_contents($jsFile);
        if (!trim($fileContent)) {
            continue;
        }

        $data['content']  .= '/* '.\fpcm\model\files\ops::removeBaseDir($jsFile).' */'.PHP_EOL.$fileContent.PHP_EOL.PHP_EOL;
    }

    $data['filesize'] = strlen($data['content']);
    $cache->write($cacheName, $data);
}

header("Content-Type: application/javascript");
//if (!FPCM_NOJSCSSPHP_FILESIZE_HEADER) {
header("Content-Length: ".$data['filesize']);
//}
exit($data['content']);