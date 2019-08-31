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

$cacheName = 'theme/cssfiles';

if ($cache->isExpired($cacheName) || \fpcm\classes\baseconfig::installerEnabled() || FPCM_DEBUG) {

    foreach (glob(__DIR__.'/*.css') as $cssFile) {

        $fileContent = file_get_contents($cssFile);
        if (!$fileContent) {
            continue;
        }

        $data['content']  .= '/* '.\fpcm\model\files\ops::removeBaseDir($cssFile).' */'.PHP_EOL.$fileContent.PHP_EOL.PHP_EOL;
    }

    $data['filesize']  = strlen($data['content']);
    $cache->write($cacheName, $data);
} else {
    $data = $cache->read($cacheName);
}

header("Content-Type: text/css");
//if (!FPCM_NOJSCSSPHP_FILESIZE_HEADER) {
header("Content-Length: ".$data['filesize']);
//}
exit($data['content']);