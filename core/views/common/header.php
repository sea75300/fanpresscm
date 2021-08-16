<?php /* @var $theView fpcm\view\viewVars */ ?>
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
        <link rel="manifest" href="<?php print $theView->themePath; ?>manifest.json">
        <?php include_once 'includefiles.php'; ?>
        <?php include_once 'vars.php'; ?>
    </head>    

    <body class="fpcm-body <?php print $theView->bodyClass; ?>" id="fpcm-body">

        <?php include_once $theView->getIncludePath('common/messagesTpl.php'); ?>
        
        <div class="fpcm ui-wrapper">

            <?php if ($theView->formActionTarget) : ?><form method="post" action="<?php print $theView->formActionTarget; ?>" enctype="multipart/form-data" id="fpcm-ui-form"><?php endif; ?>

            <header>
                <?php include_once $theView->getIncludePath('common/menutop.php'); ?>                
                <?php include_once $theView->getIncludePath('common/navigation.php'); ?>
            </header>

            <?php include_once $theView->getIncludePath('common/buttons.php'); ?>

            <div class="container-fluid mx-0 px-0 px-md-2 my-2">