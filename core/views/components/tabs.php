<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php /* @var $tab \fpcm\view\helper\tabItem */ ?>
<?php if (!isset($tabsInline) || !$tabsInline) : ?>
<div class="fpcm-content-wrapper">
<?php endif; ?>
    <div class="fpcm-ui-tabs-general ui-tabs ui-corner-all ui-widget ui-widget-content <?php print $tabsClass; ?>" id="<?php print $tabsId; ?>">
        <ul class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
            <?php foreach ($tabs as $tab) : ?><?php print $tab; ?><?php endforeach; ?>
        </ul>

        <?php foreach ($tabs as $tab) : ?>
            <?php if (!$tab->getFile()) : continue; endif; ?>
            <div <?php print $tab->getIdString(); ?> class="fpcm tabs-register ui-tabs-panel ui-corner-bottom ui-widget-content">
                <?php include $theView->getIncludePath($tab->getFile()); ?>
            </div>

        <?php endforeach; ?>

        </div>
<?php if (!isset($tabsInline) || !$tabsInline) : ?>
    </div>
<?php endif; ?>