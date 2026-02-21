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
        <link rel="stylesheet prefetch" type="text/css" href="./../style/style.css">
        <link rel="stylesheet prefetch" type="text/css" href="<?php print $api->getBootstrap(); ?>">
        <link rel="stylesheet prefetch" type="text/css" href="<?php print $api->getFontAwesome(); ?>">
    </head>
    <body class="background-brown-dark">

        <div class="col-12  col-lg-8 my-2 mr-md-1  p-0 content background-white-trans border-top-orange-bold">
            <h2>News</h2>
            <?php $api->showArticles(); ?>
        </div>

        <div class="col-12  col-lg-8 my-2 mr-md-1  p-0 content background-white-trans border-top-orange-bold">
            <h2>Module</h2>
            <?php
                try {
                    $api->getModuleApi('nkorg/calendar')->display();
                    $api->getModuleApi('nkorg/extstats')->countAll();
                    $api->getModuleApi('nkorg/polls')->displayPoll();
                } catch (Exception $exc) {
                    echo $exc->getMessage();
                }
            ?>
        </div>
    </body>
</html>