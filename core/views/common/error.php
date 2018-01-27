<!DOCTYPE HTML>
<HTML>
    <head>
        <title><?php $theView->lang->write('HEADLINE'); ?></title>
        <meta http-equiv="Content-Language" content="de">
        <meta http-equiv="content-type" content= "text/html; charset=utf-8">
        <meta name="robots" content="noindex, nofollow">  
        <link rel="shortcut icon" href="<?php print $theView->themePath; ?>favicon.png" type="image/png" /> 
        <?php include_once 'includefiles.php'; ?>
    </head>    

    <body class="fpcm-body" id="fpcm-body">

        <?php include_once 'vars.php'; ?>

        <div class="wrapper">
            <div class="fpcm-ui-center fpcm-ui-errorbox" id="fpcm-ui-errorbox">

                <span class="fa-stack fa-lg fa-5x fpcm-ui-important-text">
                    <span class="fa fa-square fa-stack-2x"></span>
                    <span class="fa fa-exclamation-triangle fa-stack-1x fa-inverse"></span>
                </span>
                
                <p><?php print \fpcm\view\helper::escapeVal($errorMessage); ?></p>
                
                <p><?php \fpcm\view\helper::linkButton('javascript:window.history.back();', 'GLOBAL_BACK'); ?></p>
                
            </div>
        </div>
        
    </body>
</html>