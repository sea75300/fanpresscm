<?php /* @var $theView fpcm\view\viewVars */ ?>
<!DOCTYPE HTML>
<HTML lang="<?php print $theView->langCode; ?>" data-bs-theme="<?php if ($theView->darkMode) : ?>dark<?php else : ?>light<?php endif; ?>">
    <head>
        <title><?php $theView->write('HEADLINE'); ?></title>
        <meta http-equiv="content-type" content= "text/html; charset=utf-8">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?php print $theView->themePath; ?>favicon.png" type="image/png" /> 
        <?php include_once 'includefiles.php'; ?>
        <?php include_once 'vars.php'; ?>
        
        <?php if ($theView->backdrop && !$theView->darkMode) : ?>
        <style>
            :root { --fpcm-var-backdrop-image: url('<?php print $theView->backdrop; ?>'); }
        </style>
        <?php endif; ?>
    </head>

    <body class="fpcm-body <?php if ($theView->darkMode) : ?>bg-gradient<?php endif; ?>" id="fpcm-body">
        <div class="position-absolute top-50 start-50 translate-middle col-12 col-md-8 col-lg-6 col-xl-4">
            <div class="shadow-lg rounded p-4 fpcm ui-background-white-50p ui-blurring">

                <header>
                    <div class="row g-0 mb-3">
                        <!-- FanPress CM News System <?php print $theView->version; ?> -->
                        <div class="col-auto">
                            <img class="border-bottom border-5 border-info fpcm <?php if ($theView->darkMode) : ?>ui-invert-1<?php endif; ?>" src="<?php print $theView->themePath; ?>logo.svg" role="presentation" alt="FanPress CM News System <?php print $theView->version; ?>">
                        </div>
                        <div class="col align-self-center">
                            <h1 class="d-none d-xl-block fs-3 <?php if ($theView->darkMode) : ?>text-light<?php endif; ?>">FanPress CM News System</h1>
                        </div>
                    </div>
                </header>

                <?php $theView->alert('danger')->setText($errorMessage)->setIcon($icon)->setClass('d-flex align-items-center justify-content-center')->setSize('2x'); ?>
                <p class="text-center"><?php $theView->linkButton('backBtn')->setUrl($backController ? $backController : 'javascript:window.history.back();')->setText('GLOBAL_BACK')->setIcon('chevron-circle-left'); ?></p>
            </div>
        </div>
    </body>
</html>


