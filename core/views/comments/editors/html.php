<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php include $theView->getIncludePath('comments/editors/html_dialogs.php'); ?>
<div class="row">
    
    <div class="btn-toolbar" role="toolbar" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">
        
        <div class="btn-group me-1 mb-1" role="group" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">

        <?php if (count($editorStyles)) : ?>
            <?php $theView->dropdown('editor-styles')->setOptions($editorStyles)->setText('EDITOR_SELECTSTYLES')->setSelected(''); ?>
        <?php endif; ?>

        <?php if (count($editorParagraphs)) : ?>
            <?php $theView->dropdown('editor-paragraphs')->setOptions($editorParagraphs)->setText('EDITOR_PARAGRAPH'); ?>
        <?php endif; ?>

        <?php if (count($editorFontsizes)) : ?>
            <?php $theView->dropdown('editor-fontsizes')->setOptions($editorFontsizes)->setText('EDITOR_SELECTFS'); ?>
        <?php endif; ?>
        </div>

        <div class="btn-group me-1 mb-1" role="group" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">
            <?php foreach ($editorButtons as $editorButton) : ?>
                <?php if ($editorButton instanceof \fpcm\view\helper\toolbarSeperator) : ?>
                    <?php print $editorButton; ?>
                <?php else : ?>
                    <?php print $editorButton->setClass('fpcm-editor-html-click')->setIconOnly(true); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    
</div>

<div class="row">
    <div style="font-size: <?php print $editorDefaultFontsize; ?>">
        <?php $theView->textarea('comment[text]')->setClass('fpcm-ui-full-width')->setValue($comment->getText(), ENT_QUOTES); ?>
    </div>
</div>