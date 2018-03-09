<?php /* @var $theView \fpcm\view\viewVars */ ?>
<form action="<?php print $theView->basePath; ?>installer&step=<?php print $step; ?><?php if ($currentStep > 1) : ?>&language=<?php print $theView->langCode; ?><?php endif; ?>" method="post" id="installerform">
    <div class="fpcm-content-wrapper fpcm-content-wrapper-installer">    
        <div class="fpcm-tabs-general" id="fpcm-tabs-installer">
            <ul>
                <?php foreach ($subTabs as $name => $descr) : ?>
                <li><a href="#tabs-installer-<?php print md5($name); ?>" <?php if ($currentStep > 1 && $tabCounter < $step) : ?>data-backlink="<?php print $theView->basePath.'installer&amp;step='.$tabCounter.'&amp;language='.$theView->langCode; ?>"<?php endif; ?>>
                    <?php $theView->write($descr); ?></a>
                </li>
                <?php $tabCounter++; ?>
                <?php endforeach; ?>
            </ul>

            <div id="tabs-installer-<?php print md5($subTemplate); ?>">
                <div class="fpcm-installer-progressbar fpcm-half-width fpcm-ui-margin-center"></div>
                <?php if ($theView->getIncludePath('installer/'.$subTemplate.'.php')) : ?>                
                    <?php include $theView->getIncludePath('installer/'.$subTemplate.'.php'); ?>
                <?php else : ?>
                    <p class="fpcm-ui-center"><?php $theView->write('GLOBAL_NOTFOUND'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="fpcm-ui-list-buttons">
        <div class="fpcm-ui-margin-center">
        <?php if ($showNextButton) : ?>
            
            <?php $theView->submitButton('SubmitNext')->setText('GLOBAL_NEXT')->setClass('fpcm-installer-next-'.$currentStep); ?>
        <?php elseif($showReload) : ?>
            <?php $theView->linkButton('reloadbtn')->setText('GLOBAL_RELOAD')->setUrl($theView->basePath.'installer&step='.$currentStep.($currentStep > 1 ? '&language='.$theView->langCode : '')); ?>
        <?php endif; ?>
        </div>
    </div>
</form>