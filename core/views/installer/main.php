<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-inner-wrapper fpcm-ui-position-absolute fpcm-ui-position-absolute-0">
    <div class="row no-gutters fpcm-ui-form-login">
        
        <div class="col-12">
            <div id="fpcm-ui-logo" class="row fpcm-ui-logo fpcm-ui-center fpcm-ui-margin-none">
                <div class="col-12">
                    <h1><span class="fpcm-ui-block">FanPress CM</span> <span class="fpcm-ui-block">News System</span></h1>
                </div>
            </div>            
        </div>

        <div class="col-12">
            <div class="fpcm-ui-tabs-general" id="fpcm-tabs-installer">
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
                    <div class="row no-gutters align-items-center fpcm-ui-padding-md-tb justify-content-center">
                        <?php include $tplFile; ?>
                    </div>

                    <?php if ($showNextButton || $showReload) : ?>
                    <div class="row no-gutters fpcm-ui-padding-md-tb">                       
                        <div class="col-12 fpcm-ui-center">
                            <div class="fpcm-ui-controlgroup">
                            <?php if ($showNextButton) : ?>
                                <?php $theView->submitButton('submitNext')->setText('GLOBAL_NEXT')->setClass('fpcm-installer-next-'.$currentStep)->setIcon('chevron-circle-right'); ?>
                            <?php elseif($showReload) : ?>
                                <?php $theView->linkButton('reloadbtn')->setText('GLOBAL_RELOAD')->setUrl($theView->basePath.'installer&step='.$currentStep.($currentStep > 1 ? '&language='.$theView->langCode : ''))->setIcon('sync'); ?>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php else : ?>
                        <div class="row no-gutters align-items-center">
                            <div class="col-12">
                                <?php $theView->icon('search')->setSize('lg')->setStack('ban fpcm-ui-important-text')->setStackTop(true); ?>
                                <?php $theView->write('GLOBAL_NOTFOUND'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>