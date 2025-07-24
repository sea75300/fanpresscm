<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php /* @var $file \fpcm\model\files\templatefile */ ?>
<?php if (!$file->isWritable()) : ?>
<div class="row modal-body p-2">
    <?php $theView->alert('danger')->setText('TEMPLATE_NOT_WRITABLE')->setIcon('lock')->setClass('d-flex align-items-center justify-content-center mb-0')->setSize('2x'); ?>
</div>
<?php endif; ?>

<div class="col-12 col-lg-7 col-xl-9">

    <div class="row my-2">

        <div class="btn-toolbar" role="toolbar" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">

            <div class="d-flex gap-1 me-1 mb-1" role="group" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">

            <?php if (count($allowedTagsList)) : ?>
                <?php $theView->dropdown('editor-tags')->setOptions($allowedTagsList)->setText('GLOBAL_HTMLTAGS_ALLOWED')->setSelected('')->setIcon('code'); ?>
            <?php endif; ?>

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

    <div class="row my-2">
        <div class="col-12">
            <div id="fpcm-id-content-ace"><?php print $theView->escapeVal($file->getContent(), ENT_QUOTES); ?></div>
            <?php $theView->textarea('templatecode')->setValue($file->getContent(), ENT_QUOTES)->setClass('d-none'); ?>
        </div>
    </div>

</div>




<?php $theView->saveButton('saveTemplate')->setClass('fpcm-ui-hidden'); ?>    