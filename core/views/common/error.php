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

    <body class="fpcm-body" id="fpcm-body">
        <div class="position-absolute top-50 start-50 translate-middle col-12 col-md-8 col-lg-6 col-xl-4">
            <div class="shadow-lg rounded p-4 fpcm ui-background-white-50p ui-blurring">

                <header>
                    <div class="row g-0 mb-3">
                        <!-- FanPress CM News System <?php print $theView->version; ?> -->
                        <div class="col-auto">
                            <img class="border-bottom border-5 border-info" src="<?php print $theView->themePath; ?>logo.svg" role="presentation" alt="FanPress CM News System <?php print $theView->version; ?>">
                        </div>
                        <div class="col align-self-center">
                            <h1 class="d-none d-xl-block fs-3">FanPress CM News System</h1>
                        </div>

                    </div>

                </header>

                <p class="text-center"><?php $theView->icon($icon.' fa-inverse')->setStack('square')->setClass('fa-5x text-danger'); ?></p>
                <p class="text-center"><?php print $errorMessage; ?></p>
                <p class="text-center"><?php $theView->linkButton('backBtn')->setUrl($backController ? $backController : 'javascript:window.history.back();')->setText('GLOBAL_BACK')->setIcon('chevron-circle-left'); ?></p>
            </div>
        </div>
    </body>
</html>


