<form action="<?php print $FPCM_BASEMODULELINK; ?>installer&step=<?php print $step; ?><?php if ($currentStep > 1) : ?>&language=<?php print $FPCM_LANG->getLangCode(); ?><?php endif; ?>" method="post" id="installerform">
    <div class="fpcm-content-wrapper fpcm-content-wrapper-installer">    
        <div class="fpcm-tabs-general" id="fpcm-tabs-installer">
            <ul>
                <?php foreach ($subTabs as $name => $descr) : ?>
                <li><a href="#tabs-installer-<?php print md5($name); ?>" <?php if ($currentStep > 1 && $tabCounter < $step) : ?>data-backlink="<?php print $FPCM_BASEMODULELINK.'installer&amp;step='.$tabCounter.'&amp;language='.$FPCM_LANG->getLangCode(); ?>"<?php endif; ?>>
                    <?php $FPCM_LANG->write($descr); ?></a>
                </li>
                <?php $tabCounter++; ?>
                <?php endforeach; ?>
            </ul>

            <div id="tabs-installer-<?php print md5($subTemplate); ?>">
                <div class="fpcm-installer-progressbar fpcm-half-width fpcm-ui-margin-center"></div>
                <?php if (file_exists(__DIR__.'/'.$subTemplate.'.php')) : ?>                
                    <?php include_once __DIR__.'/'.$subTemplate.'.php'; ?>
                <?php else : ?>
                    <p class="fpcm-ui-center"><?php $FPCM_LANG->write('GLOBAL_NOTFOUND'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
        <div class="fpcm-ui-margin-center">
        <?php if ($showNextButton) : ?>
            <?php \fpcm\model\view\helper::submitButton('SubmitNext', 'GLOBAL_NEXT', 'fpcm-installer-next-'.$currentStep); ?>
        <?php elseif($showReload) : ?>
            <?php \fpcm\model\view\helper::linkButton($FPCM_BASEMODULELINK.'installer&step='.$currentStep.($currentStep > 1 ? '&language='.$FPCM_LANG->getLangCode() : ''), 'GLOBAL_RELOAD'); ?>
        <?php endif; ?>
        </div>
    </div>
</form>