<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if (!$isWritable) : ?>
<div class="row mx-2 pt-2">
    <?php $theView->alert('danger')->setText('TEMPLATE_NOT_WRITABLE')->setIcon('lock')->setClass('d-flex align-items-center justify-content-center')->setSize('2x'); ?>
</div>
<?php endif; ?>

<div class="row g-0">

    <div class="col-12 col-lg-5 col-xl-3">
        <div class="m-2">
            <div class="list-group h-100">
                <div class="list-group-item bg-secondary text-white"><?php $theView->icon('plus'); ?> <?php $theView->write('TEMPLATE_REPLACEMENTS'); ?></div>

            <?php foreach ($replacements as $tag => $descr) : ?>
                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" data-tag="<?php print $tag; ?>">

                    <div class="d-block">
                        <h5 class="mb-1"><?php print $tag; ?></h5>
                        <p class="p-0 m-0 text-body-secondary"><?php print $descr; ?></p>
                    <?php if (isset($attributes[$tag])) : ?>
                        <div class="mt-1 fpcm ui-font-small text-body-secondary">
                            <?php $theView->write('TEMPLATE_ATTRIBUTES') ?>: <?php print implode(', ', $attributes[$tag]); ?>
                        </div>
                    <?php endif; ?>

                    </div>
                    <?php $theView->icon('plus')->setSize('lg')->setClass('ms-3'); ?>
                </a>
            <?php endforeach; ?>
            </div>

        </div>
    </div>

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
                <div id="fpcm-id-content-ace-<?php print $tplId; ?>"><?php print $theView->escapeVal($content, ENT_QUOTES); ?></div>
                <?php $theView->textarea('template[content]', 'content-'.$tplId)->setValue($content, ENT_QUOTES)->setClass('d-none'); ?>
            </div>
        </div>

    </div>
</div>

<?php $theView->hiddenInput('template[id]')->setValue($tplId); ?>

<script>
fpcm.templates.createEditorInstance('<?php print $tplId; ?>');
</script>
