<?php /* @var $theView fpcm\view\viewVars */ ?>
<!DOCTYPE HTML>
<HTML lang="<?php print $theView->langCode; ?>" <?php if ($theView->darkMode) : ?>data-bs-theme="dark"<?php endif; ?>>
    <head>
        <title><?php $theView->write('HEADLINE'); ?> <?php print $theView->version; ?> | <?php $theView->escape($theView->currentUser->getDisplayName()); ?></title>
        <meta charset="utf-8"> 
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#0073ea">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php print $theView->themePath; ?>apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="<?php print $theView->themePath; ?>android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php print $theView->themePath; ?>favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php print $theView->themePath; ?>favicon-16x16.png">        
        <link rel="shortcut icon" href="<?php print $theView->themePath; ?>favicon.ico" />
        <link rel="manifest" href="<?php print $theView->themePath; ?>manifest.json">
        <?php include_once 'includefiles.php'; ?>
        <?php include_once 'vars.php'; ?>
        
        <?php if ($theView->backdrop) : ?>
        <style>
            :root { --fpcm-var-backdrop-image: url('<?php print $theView->backdrop; ?>'); }
        </style>
        <?php endif; ?>
    </head>    

    <body class="fpcm-body <?php print $theView->bodyClass; ?>" id="fpcm-body">

        <?php if ($theView->formActionTarget) : ?><form method="post" action="<?php print $theView->formActionTarget; ?>" enctype="multipart/form-data" id="fpcm-ui-form"><?php endif; ?>


        <header>
            <?php include_once $theView->getIncludePath('common/menutop.php'); ?>
        </header>

        <div class="d-lg-flex fpcm ui-mh-100vh fpcm ui-wrapper-content">
            
            <div class="d-flex flex-column flex-shrink-0 w-auto fpcm ui-background-white-50p ui-blurring border-bottom border-1 border-secondary">

                <?php include_once $theView->getIncludePath('common/navigation.php'); ?>
                
                <hr class="d-none d-lg-block">
                <div class="d-none d-lg-block mb-2 text-center">
                    <?php $theView->button('minifyMenu')->setText('GLOBAL_HIDE')->setIcon('chevron-left')->setIconOnly()->setClass('btn-sm')->setData(['navhidden' => 0]); ?>
                    <?php $theView->linkButton('scrollTopLeft')->setText('GLOBAL_SCROLLTOP')->setUrl('#fpcm-body')->setIcon('chevron-up')->setIconOnly()->setClass('btn-sm'); ?>
                </div>
            </div>

            <div class="d-flex flex-column flex-grow-1 col-12 col-sm z-n1">
                
                <?php include_once $theView->getIncludePath('common/buttons.php'); ?>
          
                <div class="container-fluid px-2 pe-md-3 py-2">

                <?php if ($theView->deprecationNotice !== null && trim($theView->deprecationNotice)) : ?><?php $theView->alert('warning')->setText($theView->deprecationNotice); ?><?php endif; ?>
