<?php
/**
 * Combined JavaScript Files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

require_once dirname(dirname(__DIR__)).'/inc/common.php';

$data  = ['content' => '', 'filesize' => 0];
$cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');

$cacheName = 'theme/jsfiles';

if ($cache->isExpired($cacheName) || \fpcm\classes\baseconfig::installerEnabled() || FPCM_DEBUG) {

    $jsFiles = array(
        __DIR__.'/ajax.js',
        __DIR__.'/ui.js',
        __DIR__.'/notifications.js',
        __DIR__.'/system.js'
    );

    foreach ($jsFiles as $jsFile) {

        $fileContent = '/* '.\fpcm\model\files\ops::removeBaseDir($jsFile).' */'.PHP_EOL.file_get_contents($jsFile).PHP_EOL.PHP_EOL;
        if (!$fileContent) {
            continue;
        }

        $data['content']  .= $fileContent;
        $data['filesize'] += filesize($jsFile);

    }

    $cache->write($cacheName, $data, FPCM_LANGCACHE_TIMEOUT);
} else {
    $data = $cache->read($cacheName);
}

header("Content-Type: application/javascript");
if (!FPCM_NOJSCSSPHP_FILESIZE_HEADER) {
    header("Content-Length: ".$data['filesize']);
}
exit($data['content']);