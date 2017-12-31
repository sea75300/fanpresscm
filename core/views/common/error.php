<!DOCTYPE HTML>
<HTML>
    <head>
        <title><?php $FPCM_LANG->write('HEADLINE'); ?></title>
        <meta http-equiv="Content-Language" content="de">
        <meta http-equiv="content-type" content= "text/html; charset=utf-8">
        <meta name="robots" content="noindex, nofollow">  
        <link rel="shortcut icon" href="<?php print $FPCM_THEMEPATH; ?>favicon.png" type="image/png" /> 
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
                
                <p><?php print $message; ?></p>
                
                <p><?php \fpcm\model\view\helper::linkButton('javascript:window.history.back();', 'GLOBAL_BACK'); ?></p>
                
            </div>
        </div>
        
    </body>
</html>