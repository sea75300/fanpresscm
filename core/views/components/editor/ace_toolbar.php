<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="btn-toolbar" role="toolbar" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">

    <div class="d-flex gap-1 me-1 mb-1" role="group" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">

    <?php if (isset($allowedTagsList) && count($allowedTagsList)) : ?>
        <?php $theView->dropdown('editor-tags')->setOptions($allowedTagsList)->setText('GLOBAL_HTMLTAGS_ALLOWED')->setSelected('')->setIcon('code'); ?>
    <?php endif; ?>

    <?php if (isset($editorStyles) && count($editorStyles)) : ?>
        <?php $theView->dropdown('editor-styles')->setOptions($editorStyles)->setText('EDITOR_SELECTSTYLES')->setSelected('')->setIcon('css3 fa-brands'); ?>
    <?php endif; ?>

    <?php if (isset($editorParagraphs) && count($editorParagraphs)) : ?>
        <?php $theView->dropdown('editor-paragraphs')->setOptions($editorParagraphs)->setText('EDITOR_PARAGRAPH')->setIcon('paragraph'); ?>
    <?php endif; ?>

    <?php if (isset($editorFontsizes) && count($editorFontsizes)) : ?>
        <?php $theView->dropdown('editor-fontsizes')->setOptions($editorFontsizes)->setText('EDITOR_SELECTFS')->setIcon('text-height'); ?>
    <?php endif; ?>
    </div>

    <?php if (isset($editorButtons) && count($editorButtons)) : ?>
    <div class="btn-group me-1 mb-1" role="group" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">
        <?php foreach ($editorButtons as $editorButton) : ?>
            <?php if ($editorButton instanceof \fpcm\view\helper\toolbarSeperator) : ?>
                <?php print $editorButton; ?>
            <?php else : ?>
                <?php print $editorButton->setClass('fpcm-editor-ace-item')->setIconOnly(); ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php else : ?>
        <?php $theView->alert('danger')->setText('No editor buttons defined!'); ?>    
    <?php endif; ?>
</div>