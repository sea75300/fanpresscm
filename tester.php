<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'fpcmapi.php';

$api = new fpcmAPI();


$checkIp = $api->checkLockedIp();

fpcmDump('API locked?', $checkIp);

$api->showArticles();

fpcmDump('This is a poll -----');


$api->nkorg_polls_displayPoll(false);