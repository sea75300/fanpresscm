<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="d-flex flex-wrap vh-100">
    
    <header class="flex-column w-100">
        
        <nav class="navbar navbar-expand navbar-dark bg-secondary bg-gradient ui-navigation border-5 border-bottom border-white border-opacity-25">
            <div class="container-fluid g-0">

                <div class="navbar-brand px-3 me-0">
                    <!-- FanPress CM News System <?php print $theView->version; ?> -->
                    <div class="border-bottom border-5 border-info d-inline-block">
                        <img src="<?php print $theView->themePath; ?>logo.svg" alt="FanPress CM News System <?php print $theView->version; ?>" class="fpcm ui-invert-1">
                    </div>
                    <h1 class="d-none">FanPress CM News System</h1>
                </div>

                <div class="navbar-nav navbar d-flex gap-1  me-2">
                <?php foreach ($theView->buttons as $button) : ?>
                    <?php $button->setClass('shadow-sm'); ?>
                    <div class="nav-item">
                        <?php print $button; ?>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </nav>

        <div class="px-2 py-3 bg-light bg-opacity-25 fpcm ui-blurring">
            <div class="progress fpcm ui-progressbar-sm" id="fpcm-id-progress-installer">
                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="<?php print $step; ?>" aria-valuemin="1" aria-valuemax="7" style="width: <?php print $progressWidth; ?>%"></div>
            </div>
        </div>

    </header>
    
    <article class="flex-column w-100">
        <div class="d-flex justify-content-center">
            <div class="bg-light bg-opacity-50 fpcm ui-blurring rounded-5 shadow-lg p-5 m-3 <?php if ($fill) : ?>flex-fill<?php endif ?>">
                
                <h3 class="mb-5 text-center"><?php $theView->icon($icon); ?> <?php $theView->write($headline); ?></h3>
                
                <?php include_once $theView->getIncludePath('installer/'. $tpl . '.php'); ?>
            </div>
        </div>
    </article>  
    
    
    <footer class="flex-column w-100 align-self-end">    
        <div class="row row-cols-1 row-cols-md-2 py-2 bg-dark text-light fs-6 align-items-end">
            <div class="col bg-dark">
                &copy; 2011-<?php print date('Y'); ?> <a class="text-light" href="https://nobody-knows.org/download/fanpress-cm/" target="_blank" rel="noreferrer,noopener,external">nobody-knows.org</a>                
            </div>
            <div class="col">
                <div class="d-flex justify-content-md-end">
                    <b><?php $theView->write('VERSION'); ?>:</b>&nbsp;<?php print $theView->version; ?>                        
                </div>
            </div>
        </div>
    </footer>    
</div>

