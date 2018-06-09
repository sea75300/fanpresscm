<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-full-height">
    <div class="col-12">
        <div class="fpcm-content-wrapper fpcm-ui-full-height">
            <div class="fpcm-ui-tabs-general" id="fpcm-tabs-templates">
                <ul>
                    <?php foreach ($tabs as $tab) : ?><?php print $tab; ?><?php endforeach; ?>
                </ul>

                <div id="tab-article-editor-templates">
                    <?php include $theView->getIncludePath('templates/article_templates.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-templatepreview-layer"></div>
