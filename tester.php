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
        <h2>News</h2>
    <?php



    //printf('<script src="%s"></script>', $api->getPublicJsFile());

    //fpcmDump('API locked?', $api->checkLockedIp());



    //fpcmDump('Articles -----');
    $api->showArticles();
?>
        <h2>Module</h2>
<?php
    
    $api->getModuleApi('nkorg/calendar')->display();
    
    $api->getModuleApi('nkorg/extstats')->countAll();
    
    $api->getModuleApi('nkorg/polls')->displayPoll();
    ?>
    </body>
</html>