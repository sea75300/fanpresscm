<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $tab \fpcm\view\helper\tabItem */ ?>
<?php if (!isset($tabsInline) || !$tabsInline) : ?>
<div class="row no-gutters">
    <div class="col-12">
        <div class="fpcm-content-wrapper">
<?php endif; ?>
            <div class="fpcm-ui-tabs-general" id="<?php print $tabsId; ?>">
                <ul>
                    <?php foreach ($tabs as $tab) : ?><?php print $tab; ?><?php endforeach; ?>
                </ul>

            <?php foreach ($tabs as $tab) : ?>
                <?php if (!$tab->getFile()) : continue; endif; ?>
                <div<?php print $tab->getIdString(); ?> class="fpcm tabs-reegister">
                    <?php include $theView->getIncludePath($tab->getFile()); ?>
                </div>
   
            <?php endforeach; ?>

            </div>
<?php if (!isset($tabsInline) || !$tabsInline) : ?>
        </div>
    </div>
</div>
<?php endif; ?>