<?php
/**
 * Combined JavaScript Files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

require_once dirname(dirname(__DIR__)).'/inc/common.php';

$data = array('content' => '', 'filesize' => 0);
$cache = new \fpcm\classes\cache('jsfiles', 'theme');

if ($cache->isExpired() || \fpcm\classes\baseconfig::installerEnabled() || FPCM_DEBUG) {

    $jsFiles = array(
        __DIR__.'/ui.js',
        __DIR__.'/ajax.js',
        __DIR__.'/functions.js',
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

    $cache->write($data, FPCM_LANGCACHE_TIMEOUT);
} else {
    $data = $cache->read();
}

header("Content-Type: application/javascript");
if (!FPCM_NOJSCSSPHP_FILESIZE_HEADER) {
    header("Content-Length: ".$data['filesize']);
}
die($data['content']);