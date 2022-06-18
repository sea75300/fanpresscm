<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'fpcmapi.php';

$api = new fpcmAPI();
fpcmDump('API locked?', $api->checkLockedIp());


fpcmDump('This is a poll -----');
$api->nkorg_polls_displayPoll(false);



fpcmDump('Articles -----');
$api->showArticles();