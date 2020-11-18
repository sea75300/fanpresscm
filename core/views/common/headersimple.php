<!DOCTYPE HTML>
<HTML lang="<?php print $theView->langCode; ?>">
    <head>
        <title><?php $theView->write('HEADLINE'); ?></title>
        <meta charset="utf-8"> 
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php print $theView->themePath; ?>apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php print $theView->themePath; ?>favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php print $theView->themePath; ?>favicon-16x16.png">        
        <link rel="shortcut icon" href="<?php print $theView->themePath; ?>favicon.ico" /> 
        <?php include_once 'includefiles.php'; ?>
        <?php include_once 'vars.php'; ?>
    </head>     

    <body class="fpcm-body fpcm-body-simple <?php print $theView->bodyClass; ?>" id="fpcm-body">
        
        <?php include_once $theView->getIncludePath('common/messagesTpl.php'); ?>

        <?php if ($theView->formActionTarget) : ?><form method="post" action="<?php print $theView->formActionTarget; ?>" enctype="multipart/form-data" id="fpcm-ui-form"><?php endif; ?>

        <div class="row fpcm-ui-full-view-height">
            <div class="col-12 px-0">
