<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-templates-articles" class="fpcm-template-tab" data-tpl="1"><?php $theView->write('TEMPLATE_HL_ARTICLES'); ?></a></li>
            <?php if (isset($contentArticleSingle) && isset($replacementsArticleSingle)) : ?>
            <li><a href="#tabs-templates-article" class="fpcm-template-tab" data-tpl="2"><?php $theView->write('TEMPLATE_HL_ARTICLE_SINGLE'); ?></a></li>
            <?php endif; ?>
            <li><a href="#tabs-templates-comments" class="fpcm-template-tab" data-tpl="3"><?php $theView->write('TEMPLATE_HL_COMMENTS'); ?></a></li>
            <li><a href="#tabs-templates-commentform" class="fpcm-template-tab" data-tpl="4"><?php $theView->write('TEMPLATE_HL_COMMENTFORM'); ?></a></li>
            <li><a href="#tabs-templates-latestnews" class="fpcm-template-tab" data-tpl="5"><?php $theView->write('TEMPLATE_HL_LATESTNEWS'); ?></a></li>
            <li><a href="#tabs-templates-tweet" class="fpcm-template-tab" data-tpl="6"><?php $theView->write('TEMPLATE_HL_TWEET'); ?></a></li>
            <li><a href="#tabs-templates-articletpl" class="fpcm-template-tab" data-tpl="7"><?php $theView->write('TEMPLATE_HL_DRAFTS'); ?></a></li>
        </ul>
        <div id="tabs-templates-articles">
            <?php include $theView->getIncludePath('templates/articles.php'); ?>
        </div>
        <?php if (isset($contentArticleSingle) && isset($replacementsArticleSingle)) : ?>
        <div id="tabs-templates-article">
            <?php include $theView->getIncludePath('templates/articles.php'); ?>
        </div>
        <?php endif; ?>
        <div id="tabs-templates-comments">
            <?php include $theView->getIncludePath('templates/comments.php'); ?>
        </div>
        <div id="tabs-templates-commentform">
            <?php include $theView->getIncludePath('templates/commentform.php'); ?>
        </div>             
        <div id="tabs-templates-latestnews">
            <?php include $theView->getIncludePath('templates/latestnews.php'); ?>
        </div>             
        <div id="tabs-templates-tweet">
            <?php include $theView->getIncludePath('templates/tweet.php'); ?>
        </div>             
        <div id="tabs-templates-articletpl">
            <?php include $theView->getIncludePath('templates/article_templates.php'); ?>
        </div>
    </div>
</div>

<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-templatepreview-layer"></div>
