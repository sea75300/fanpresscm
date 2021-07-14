<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div id="fpcm-tabs-templates">
        <ul class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
            <?php foreach ($tabs as $tab) : ?><?php print $tab; ?><?php endforeach; ?>
        </ul>

        <?php if ($theView->permissions->system->drafts) : ?>
        <div id="tab-article-editor-templates" class="fpcm tabs-register ui-tabs-panel ui-corner-bottom ui-widget-content">
            <?php include $theView->getIncludePath('templates/article_templates.php'); ?>
        </div>
        <?php endif; ?>
    </div>
</div>
