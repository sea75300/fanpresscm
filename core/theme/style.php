<?php
/**
 * Combined JavaScript Files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

require_once dirname(dirname(__DIR__)).'/inc/common.php';

$data  = ['content' => '', 'filesize' => 0];
$cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');

$cacheName = 'theme/cssfiles';

if ($cache->isExpired($cacheName) || \fpcm\classes\baseconfig::installerEnabled() || FPCM_DEBUG) {

    foreach (glob(__DIR__.'/*.css') as $cssFile) {

        $fileContent = '/* '.\fpcm\model\files\ops::removeBaseDir($cssFile).' */'.PHP_EOL.file_get_contents($cssFile).PHP_EOL.PHP_EOL;        
        $contentSize = strlen($fileContent);
        
        if (!$fileContent) {
            continue;
        }

        $data['content']  .= $fileContent;
        $data['filesize'] += (filesize($cssFile) + $contentSize);

    }

    $cache->write($cacheName, $data, FPCM_LANGCACHE_TIMEOUT);
} else {
    $data = $cache->read($cacheName);
}

header("Content-Type: text/css");
if (!FPCM_NOJSCSSPHP_FILESIZE_HEADER) {
    header("Content-Length: ".$data['filesize']);
}
die($data['content']);