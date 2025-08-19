 <?php
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'fpcmapi.php';

    if (defined('FPCM_FLAGS_DATABASE_CONNECTION_ERROR') && FPCM_FLAGS_DATABASE_CONNECTION_ERROR) {
        exit('Databse connection failed!');
    }

    $api = new fpcmAPI();
 ?>
<!DOCTYPE HTML>
<HTML lang="de">
    <head>
        <title>FPCM Tester</title>
        <?php //$api->getPublicHeader(); ?>
    </head>
    <body>
    <?php



    //printf('<script src="%s"></script>', $api->getPublicJsFile());

    //fpcmDump('API locked?', $api->checkLockedIp());



    //fpcmDump('Articles -----');
    $api->showArticles();


    //fpcmDump('This is a poll -----');
    //$api->nkorg_polls_displayPoll(false);
    ?>
    </body>
</html>