<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $tab \fpcm\view\helper\tabItem */ ?>
<?php if (!isset($tabsInline) || !$tabsInline) : ?>
<div class="fpcm-content-wrapper">
<?php endif; ?>
    <div class="fpcm ui-tabs-wrapper" id="<?php print $tabsId; ?>">
        <ul class="nav nav-tabs <?php print $tabsClass; ?>" role="tablist">
            <?php foreach ($tabs as $tab) : ?><?php print $tab; ?><?php endforeach; ?>
        </ul>    
    
        <div class="tab-content <?php if (!isset($hideTabBackground)) : ?>fpcm ui-background-white-50p<?php endif; ?>">
        <?php foreach ($tabs as $tab) : ?>
            <?php if (!$tab->getFile()) : continue; endif; ?>
            <div <?php print $tab->getIdString(); ?> class="tab-pane fade <?php if ($tab->isActive()) : ?>show active<?php endif; ?>" role="tabpanel" aria-labelledby="fpcm-tab-<?php print $tab->getId(); ?>">
                    <?php include $theView->getIncludePath($tab->getFile()); ?>
                </div>
        <?php endforeach; ?>
        </div>
    </div>        
<?php if (!isset($tabsInline) || !$tabsInline) : ?>
</div>
<?php endif; ?>

