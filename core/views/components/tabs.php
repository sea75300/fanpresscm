<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $tab \fpcm\view\helper\tabItem */ ?>
<?php if (!isset($tabsInline) || !$tabsInline) : ?>
<div class="fpcm content-wrapper">
<?php endif; ?>
    <div class="fpcm ui-tabs-wrapper <?php print $tabsClass; ?>" id="<?php print $tabsId; ?>">
        <ul class="nav nav-tabs flex-column flex-sm-row" role="tablist">
            <?php foreach ($tabs as $tabIdx => $tab) : ?><?php print $tab->setSaveIndex($tabIdx); ?><?php endforeach; ?>
        </ul>    
    
        <div class="tab-content <?php if (!isset($hideTabBackground)) : ?>fpcm ui-background-white-50p<?php endif; ?>">
        <?php foreach ($tabs as $tab) : ?>
            
            <div id="fpcm-tab-<?php print $tab->getId(); ?>-pane" class="tab-pane fade <?php if ($tab->isActive()) : ?>show active<?php endif; ?>" role="tabpanel" aria-labelledby="fpcm-tab-<?php print $tab->getId(); ?>">
                <?php if ($tab->getFile()) : ?>
                    <?php include $theView->getIncludePath($tab->getFile()); ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>        
<?php if (!isset($tabsInline) || !$tabsInline) : ?>
</div>
<?php endif; ?>

<?php if (trim($theView->includeForms)) : ?>
<?php include $theView->getIncludePath($theView->includeForms); ?>
<?php endif; ?>

