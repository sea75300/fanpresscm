<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php include $theView->getIncludePath('articles/editors/html_dialogs.php'); ?>
<div class="row">

    <div class="btn-toolbar" role="toolbar" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">

        <div class="d-flex gap-1 me-1 mb-1" role="group" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">

        <?php if (count($editorStyles)) : ?>
            <?php $theView->dropdown('editor-styles')->setOptions($editorStyles)->setText('EDITOR_SELECTSTYLES')->setSelected('')->setIcon('css3 fa-brands'); ?>
        <?php endif; ?>

        <?php if (count($editorParagraphs)) : ?>
            <?php $theView->dropdown('editor-paragraphs')->setOptions($editorParagraphs)->setText('EDITOR_PARAGRAPH')->setIcon('paragraph'); ?>
        <?php endif; ?>

        <?php if (count($editorFontsizes)) : ?>
            <?php $theView->dropdown('editor-fontsizes')->setOptions($editorFontsizes)->setText('EDITOR_SELECTFS')->setIcon('text-height'); ?>
        <?php endif; ?>
        </div>

        <div class="btn-group me-1 mb-1" role="group" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">
            <?php foreach ($editorButtons as $editorButton) : ?>
                <?php if ($editorButton instanceof \fpcm\view\helper\toolbarSeperator) : ?>
                    <?php print $editorButton; ?>
                <?php else : ?>
                    <?php print $editorButton->setClass('fpcm-editor-html-click')->setIconOnly(); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<div class="row">
    <div id="fpcm-id-content-ace"><?php print $theView->escapeVal($article->getContent(), ENT_QUOTES); ?></div>

    <?php $theView->textarea('article[content]')->setClass('d-none')->setValue($article->getContent(), ENT_QUOTES); ?>
</div>