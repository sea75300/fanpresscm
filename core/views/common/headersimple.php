<!DOCTYPE HTML>
<HTML lang="<?php print $theView->langCode; ?>">
    <head>
        <title><?php $theView->write('HEADLINE'); ?></title>
        <meta charset="utf-8"> 
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include_once 'includefiles.php'; ?>
        <?php include_once 'vars.php'; ?>
    </head>     

    <body class="fpcm-body fpcm-body-simple <?php print $theView->bodyClass; ?>" id="fpcm-body">
        
        <div id="fpcm-messages" class="fpcm-ui-position-absolute fpcm-ui-position-right-0"></div>

        <?php if ($theView->formActionTarget) : ?><form method="post" action="<?php print $theView->formActionTarget; ?>" enctype="multipart/form-data" id="fpcm-ui-form"><?php endif; ?>

        <div class="row fpcm-ui-full-view-height">
            <div class="col-12 fpcm-ui-padding-none-lr">
