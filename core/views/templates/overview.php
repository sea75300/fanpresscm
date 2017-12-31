<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-code"></span> <?php $FPCM_LANG->write('HL_OPTIONS_TEMPLATES'); ?>
    </h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=system/templates" enctype="multipart/form-data">
        
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-templates-articles" class="fpcm-template-tab" data-tpl="1"><?php $FPCM_LANG->write('TEMPLATE_HL_ARTICLES'); ?></a></li>
                <?php if (isset($contentArticleSingle) && isset($replacementsArticleSingle)) : ?>
                <li><a href="#tabs-templates-article" class="fpcm-template-tab" data-tpl="2"><?php $FPCM_LANG->write('TEMPLATE_HL_ARTICLE_SINGLE'); ?></a></li>
                <?php endif; ?>
                <li><a href="#tabs-templates-comments" class="fpcm-template-tab" data-tpl="3"><?php $FPCM_LANG->write('TEMPLATE_HL_COMMENTS'); ?></a></li>
                <li><a href="#tabs-templates-commentform" class="fpcm-template-tab" data-tpl="4"><?php $FPCM_LANG->write('TEMPLATE_HL_COMMENTFORM'); ?></a></li>
                <li><a href="#tabs-templates-latestnews" class="fpcm-template-tab" data-tpl="5"><?php $FPCM_LANG->write('TEMPLATE_HL_LATESTNEWS'); ?></a></li>
                <li><a href="#tabs-templates-tweet" class="fpcm-template-tab" data-tpl="6"><?php $FPCM_LANG->write('TEMPLATE_HL_TWEET'); ?></a></li>
                <li><a href="#tabs-templates-articletpl" class="fpcm-template-tab" data-tpl="7"><?php $FPCM_LANG->write('TEMPLATE_HL_DRAFTS'); ?></a></li>
            </ul>
            <div id="tabs-templates-articles">
                <?php include __DIR__.'/articles.php'; ?>
            </div>
            <?php if (isset($contentArticleSingle) && isset($replacementsArticleSingle)) : ?>
            <div id="tabs-templates-article">
                <?php include __DIR__.'/article.php'; ?>
            </div>
            <?php endif; ?>
            <div id="tabs-templates-comments">
                <?php include __DIR__.'/comments.php'; ?>
            </div>
            <div id="tabs-templates-commentform">
                <?php include __DIR__.'/commentform.php'; ?>
            </div>             
            <div id="tabs-templates-latestnews">
                <?php include __DIR__.'/latestnews.php'; ?>
            </div>             
            <div id="tabs-templates-tweet">
                <?php include __DIR__.'/tweet.php'; ?>
            </div>             
            <div id="tabs-templates-articletpl">
                <?php include __DIR__.'/article_templates.php'; ?>
            </div>
        </div>
        
        <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons" id="template_buttons">
            <div class="fpcm-ui-margin-center">
                <?php \fpcm\model\view\helper::linkButton('#', 'GLOBAL_PREVIEW', 'showpreview', 'fpcm-ui-preview'); ?>
                <?php \fpcm\model\view\helper::saveButton('saveTemplates'); ?>
            </div>
        </div>
    </form>
</div>

<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-templatepreview-layer"></div>
