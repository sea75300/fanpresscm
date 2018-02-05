<form action="<?php print $theView->basePath; ?>installer&step=<?php print $step; ?><?php if ($currentStep > 1) : ?>&language=<?php print $theView->lang->getLangCode(); ?><?php endif; ?>" method="post" id="installerform">
    <div class="fpcm-content-wrapper fpcm-content-wrapper-installer">    
        <div class="fpcm-tabs-general" id="fpcm-tabs-installer">
            <ul>
                <?php foreach ($subTabs as $name => $descr) : ?>
                <li><a href="#tabs-installer-<?php print md5($name); ?>" <?php if ($currentStep > 1 && $tabCounter < $step) : ?>data-backlink="<?php print $theView->basePath.'installer&amp;step='.$tabCounter.'&amp;language='.$theView->lang->getLangCode(); ?>"<?php endif; ?>>
                    <?php $theView->lang->write($descr); ?></a>
                </li>
                <?php $tabCounter++; ?>
                <?php endforeach; ?>
            </ul>

            <div id="tabs-installer-<?php print md5($subTemplate); ?>">
                <div class="fpcm-installer-progressbar fpcm-half-width fpcm-ui-margin-center"></div>
                <?php if ($theView->getIncludePath('installer/'.$subTemplate.'.php')) : ?>                
                    <?php include $theView->getIncludePath('installer/'.$subTemplate.'.php'); ?>
                <?php else : ?>
                    <p class="fpcm-ui-center"><?php $theView->lang->write('GLOBAL_NOTFOUND'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
        <div class="fpcm-ui-margin-center">
        <?php if ($showNextButton) : ?>
            <?php \fpcm\view\helper::submitButton('SubmitNext', 'GLOBAL_NEXT', 'fpcm-installer-next-'.$currentStep); ?>
        <?php elseif($showReload) : ?>
            <?php \fpcm\view\helper::linkButton($theView->basePath.'installer&step='.$currentStep.($currentStep > 1 ? '&language='.$theView->lang->getLangCode() : ''), 'GLOBAL_RELOAD'); ?>
        <?php endif; ?>
        </div>
    </div>
</form>