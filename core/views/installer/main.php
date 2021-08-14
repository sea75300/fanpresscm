<?php /* @var $theView \fpcm\view\viewVars */ ?>

    <nav class="navbar navbar-expand-sm nav navbar-light bg-primary">
        <div class="container-fluid">

            <div class="navbar-brand">
                <!-- FanPress CM News System <?php print $theView->version; ?> -->                
                <img src="<?php print $theView->themePath; ?>logo.svg" role="presentation" alt="FanPress CM News System <?php print $theView->version; ?>">
                <h1 class="d-none">FanPress CM News System</h1>
            </div>

            <div class="navbar-nav">
            <?php foreach ($theView->buttons as $button) : ?>
                <?php $button->setClass('shadow-sm'); ?>
                <div class="nav-item">
                    <?php print $button; ?>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </nav>


    <div class="row g-0">
        <div class="container-fluid mx-0 px-0 px-md-2 my-3">

        <?php include_once $theView->getIncludePath('components/tabs.php'); ?>

        </div>

    </div>

    <footer>    
        <div class="row row-cols-1 row-cols-md-2 py-2 bg-dark text-light fs-6">
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