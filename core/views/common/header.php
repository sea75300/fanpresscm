<!DOCTYPE HTML>
<HTML lang="<?php print $theView->langCode; ?>">
    <head>
        <title><?php $theView->write('HEADLINE'); ?></title>
        <meta http-equiv="content-type" content= "text/html; charset=utf-8">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?php print $theView->themePath; ?>favicon.png" type="image/png" /> 
        <?php include_once 'includefiles.php'; ?>
    </head>    

    <body class="fpcm-body" id="fpcm-body">

        <?php if ($theView->formActionTarget) : ?><form method="post" action="<?php print $theView->formActionTarget; ?>" enctype="multipart/form-data"><?php endif; ?>

            <?php include_once 'vars.php'; ?>

            <div id="fpcm-messages" class="fpcm-messages"></div>
            <?php if (!empty($includeManualCheck)) : ?>
                <div class="fpcm-editor-dialog" id="fpcm-dialog-manualupdate-check"></div>
            <?php endif; ?>

            <div class="fpcm-wrapper-left fpcm-ui-background-white-50p <?php if (in_array($theView->currentModule, ['system/login', 'installer'] )) : ?>fpcm-wrapper-fixed<?php endif; ?>" id="fpcm-wrapper-left">

                <div id="fpcm-ui-logo" class="fpcm-ui-logo fpcm-ui-center">
                    <h1><span class="fpcm-ui-block">FanPress CM</span> <span class="fpcm-ui-block">News System</span></h1>
                </div>

                <?php include_once $theView->getIncludePath('common/navigation.php'); ?>

                <div class="fpcm-footer fpcm-ui-font-small fpcm-ui-center fpcm-footer-left">
                    <div class="fpcm-footer-text">
                        <b>Version</b> <?php print $theView->version; ?><br>
                        &copy; 2011-<?php print date('Y'); ?> <a href="https://nobody-knows.org/download/fanpress-cm/" target="_blank">nobody-knows.org</a>                    
                    </div>
                </div>

            </div>

            <div class="fpcm-wrapper <?php if (in_array($theView->currentModule, array('system/login', 'installer'))) : ?>fpcm-wrapper-fixed<?php endif; ?>" id="fpcm-wrapper-right">
                <?php include_once $theView->getIncludePath('common/menutop.php'); ?>
                <?php include_once $theView->getIncludePath('common/buttons.php'); ?>