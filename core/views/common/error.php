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
        <?php include_once 'vars.php'; ?>

    </head>  

    <body class="fpcm-body <?php print $theView->bodyClass; ?>" id="fpcm-body">

        <div class="row g-0 fpcm-ui-full-view-height m-2 ms-0 justify-content-center">
            <div class="fpcm ui-form-login col-12 col-md-10 col-lg-8 col-xl-5 align-self-center">
                <div class="fpcm ui-background-white-50p ui-blurring fpcm-ui-border-radius-all p-3 py-md-3 px-md-4 fpcm ui-align-center">

                    <h1 class="fpcm-ui-margin-md-bottom"><?php $theView->icon('chevron-right'); ?> <span>FanPress CM</span> <span>News System</span></h1>

                    <p class=""><?php $theView->icon($icon.' fa-inverse')->setStack('square')->setClass('fa-5x text-danger'); ?></p>
                    <p><?php print $errorMessage; ?></p>
                    <p><?php $theView->linkButton('backBtn')->setUrl($backController ? $backController : 'javascript:window.history.back();')->setText('GLOBAL_BACK')->setIcon('chevron-circle-left'); ?></p>

                </div>
            </div>
        </div>
        
    </body>
</html>


