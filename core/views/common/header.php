<?php /* @var $theView fpcm\view\viewVars */ ?>
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

        <div class="row fpcm-ui-position-absolute fpcm-ui-position-left-0 fpcm-ui-position-right-0 fpcm-ui-position-top-0 fpcm-ui-position-bottom-0">

            <?php include_once 'vars.php'; ?>

            <div id="fpcm-messages" class="fpcm-messages"></div>
            <?php if (!empty($includeManualCheck)) : ?>
                <div class="fpcm-editor-dialog" id="fpcm-dialog-manualupdate-check"></div>
            <?php endif; ?>

            <div class="<?php if ($theView->fullWrapper) : ?>fpcm-ui-hidden<?php else : ?>col-sm-12 col-md-2<?php endif; ?> fpcm-ui-padding-none-lr fpcm-ui-background-white-50p" id="fpcm-wrapper-left">

                <div id="fpcm-ui-logo" class="fpcm-ui-logo fpcm-ui-center">
                    <h1><span class="fpcm-ui-block">FanPress CM</span> <span class="fpcm-ui-block">News System</span></h1>
                </div>

                <?php include_once $theView->getIncludePath('common/navigation.php'); ?>

                <div id="fpcm-footer-left" class="col-md-12 fpcm-footer <?php if (!$theView->loggedIn) : ?>fpcm-ui-position-absolute fpcm-ui-position-bottom-0 <?php endif; ?>fpcm-ui-margintop-lg fpcm-ui-padding-none-lr  fpcm-ui-font-small fpcm-ui-center">
                    <div class="fpcm-footer-text">
                        <b>Version</b> <?php print $theView->version; ?><br>
                        &copy; 2011-<?php print date('Y'); ?> <a href="https://nobody-knows.org/download/fanpress-cm/" target="_blank">nobody-knows.org</a>                    
                    </div>
                </div>

            </div>

            <div class="<?php if ($theView->fullWrapper) : ?>col-sm-12<?php else : ?>col-md-10<?php endif; ?> fpcm-ui-padding-none-lr" id="fpcm-wrapper-right">
                <?php include_once $theView->getIncludePath('common/menutop.php'); ?>
                <?php include_once $theView->getIncludePath('common/buttons.php'); ?>