<?php /* @var $theView \fpcm\view\viewVars */ ?>

<div class="fpcm-ui-full-view-height">
    <div class="row g-0 fpcm ui-background-white-50p">
        <div class="col-12 col-md-6 fpcm-ui-ellipsis">
            <h1 class="fpcm-ui-padding-lg-lr"><?php $theView->icon('chevron-right '); ?> <span>FanPress CM</span> <span>News System</span></h1>
        </div>               
    </div>

    <div class="container-fluid">
        <?php include_once $theView->getIncludePath('common/buttons.php'); ?>

        <div class="row g-0 mt-3">
            <div class="col-12">
                <div id="fpcm-tabs-installer">
                    <ul>
                        <?php foreach ($subTabs as $name => $data) : ?>
                        <li><a href="#tabs-installer-<?php print md5($name); ?>" <?php if ($data['back']) : ?>data-backlink="<?php print $theView->basePath.'installer&amp;step='.$data['back'].'&amp;language='.$theView->langCode; ?>"<?php endif; ?>>
                            <?php $theView->icon($data['icon']); ?>
                            <?php $theView->write($data['descr']); ?></a>
                        </li>
                        <?php $tabCounter++; ?>
                        <?php endforeach; ?>
                    </ul>

                    <div id="tabs-installer-<?php print md5($subTemplate); ?>">
                        <?php $tplFile = $theView->getIncludePath('installer/'.$subTemplate.'.php'); ?>
                        <?php if ($tplFile) : ?>                
                        <div class="row g-0 align-items-center fpcm-ui-padding-md-tb justify-content-center fpcm-ui-full-height">
                            <?php include $tplFile; ?>
                        </div>

                        <?php else : ?>
                            <div class="row g-0 align-items-center">
                                <div class="col-12">
                                    <?php $theView->icon('search')->setSize('lg')->setStack('ban text-danger')->setStackTop(true); ?>
                                    <?php $theView->write('GLOBAL_NOTFOUND'); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include_once $theView->getIncludePath('common/footer.php'); ?>
    </div>
    
</div>