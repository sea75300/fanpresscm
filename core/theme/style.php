<?php
/**
 * Combined JavaScript Files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

require_once dirname(dirname(__DIR__)).'/inc/common.php';

$data = array('content' => '', 'filesize' => 0);
$cache = new \fpcm\classes\cache('cssfiles', 'theme');

if ($cache->isExpired() || \fpcm\classes\baseconfig::installerEnabled() || FPCM_DEBUG) {

    $cssFiles = array(
        __DIR__.'/style.css',
        __DIR__.'/responsive.css',
        __DIR__.'/icons.css'
    );

    foreach ($cssFiles as $cssFile) {

        $fileContent = '/* '.\fpcm\model\files\ops::removeBaseDir($cssFile).' */'.PHP_EOL.file_get_contents($cssFile).PHP_EOL.PHP_EOL;
        if (!$fileContent) {
            continue;
        }

        $data['content']  .= $fileContent;
        $data['filesize'] += filesize($cssFile);

    }

    $cache->write($data, FPCM_LANGCACHE_TIMEOUT);
} else {
    $data = $cache->read();
}

header("Content-Type: text/css");
if (!FPCM_NOJSCSSPHP_FILESIZE_HEADER) {
    header("Content-Length: ".$data['filesize']);
}
die($data['content']);