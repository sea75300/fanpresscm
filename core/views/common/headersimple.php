<!DOCTYPE HTML>
<HTML lang="<?php print $theView->lang->getLangCode(); ?>">
    <head>
        <title><?php $theView->lang->write('HEADLINE'); ?></title>
        <meta http-equiv="content-type" content= "text/html; charset=utf-8">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?php print $theView->themePath; ?>favicon.png" type="image/png" /> 
        <?php include_once 'includefiles.php'; ?>
    </head>     

    <body class="fpcm-body fpcm-body-nogradient" id="fpcm-body">
        
        <?php if ($theView->formActionTarget) : ?><form method="post" action="<?php print $theView->formActionTarget; ?>"><?php endif; ?>
        
        <?php include_once 'vars.php'; ?>
        
            <div id="fpcm-messages" class="fpcm-messages"></div>

            <div class="wrapper">
