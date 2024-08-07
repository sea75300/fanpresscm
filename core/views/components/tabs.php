<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $tab \fpcm\view\helper\tabItem */ ?>
<div class="fpcm ui-tabs-wrapper <?php print $tabsClass; ?>" id="<?php print $tabsId; ?>">
    <ul class="nav nav-tabs flex-column flex-md-row border-5 border-bottom border-white border-opacity-25" role="tablist">
    <?php foreach ($tabs as $tabIdx => $tab) : ?>
        <?php print $tab->setSaveIndex($tabIdx); ?>
    <?php endforeach; ?>
    </ul>    

    <div class="tab-content <?php if (!isset($hideTabBackground)) : ?>fpcm ui-background-white-50p<?php endif; ?>">
    <?php foreach ($tabs as $tab) : ?>

        <div id="fpcm-tab-<?php print $tab->getId(); ?>-pane" class="fpcm ui-mh-100vh tab-pane fade <?php if ($tab->isActive()) : ?>show active<?php endif; ?>" role="tabpanel" aria-labelledby="fpcm-tab-<?php print $tab->getId(); ?>">
            <?php if ($tab->getFile() && $tab->canPreload()) : ?>
                <?php include $tab->getFile(); ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>
</div>
<?php if ($theView->debugMode) : ?>
<div class="d-flex justify-content-end m-3 text-body-tertiary fpcm ui-font-small">
    Tab: <?php $theView->escape($tabsId); ?>
</div>
<?php endif; ?>  

<?php if ($theView->includeForms !== null && trim($theView->includeForms)) : ?>
<?php include $theView->getIncludePath($theView->includeForms); ?>
<?php endif; ?>

