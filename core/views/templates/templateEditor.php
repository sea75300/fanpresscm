<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="border-top border-5 border-primary">
    <?php if (!$isWritable) : ?>
    <div class="row pb-3">
        <div class="col-auto align-self-center"><?php $theView->icon('lock')->setSize('2x')->setClass('text-danger'); ?></div>
        <div class="col-auto align-self-center"><?php $theView->write('TEMPLATE_NOT_WRITABLE'); ?></div>
    </div>
    <?php endif; ?>

    <div class="row g-0">

        <div class="col-12 col-lg-5 col-xl-3">
            <div class="m-2">
                <div class="list-group h-100">
                    <div class="list-group-item bg-secondary text-white"><?php $theView->icon('plus'); ?> <?php $theView->write('TEMPLATE_REPLACEMENTS'); ?></div>

                <?php foreach ($replacements as $tag => $descr) : ?>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center fpcm-ui-template-tags" data-tag="<?php print $tag; ?>">

                        <div class="d-block">
                            <h5 class="mb-1"><?php print $tag; ?></h5>
                            <p class="p-0 m-0 text-secondary"><?php print $descr; ?></p>
                        <?php if (isset($attributes[$tag])) : ?>
                            <div class="mt-1 fpcm ui-font-small">
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
                    
            <?php if (count($allowedTagsList)) : ?>
            <div class="row mt-2">

                <div class="btn-toolbar" role="toolbar" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">
                <?php foreach ($allowedTagsList as $allowedTags) : ?>
                <div class="btn-group m-1" role="group" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">
                    <?php foreach ($allowedTags as $i => $tag) : ?>
                        <?php $theView->button('tps-editor-'.substr($tag, 1, -1))->setText(htmlentities($tag))->setClass('fpcm-editor-html-click')->setData(['htmltag' => substr($tag, 1, -1)]); ?>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
                </div>

            </div>
            <?php endif; ?>


            <div class="row my-2">
                <div class="col-12">
                    <?php $theView->textarea('template[content]', 'content_'.$tplId)->setValue($content, ENT_QUOTES)->setClass('fpcm-editor-html-click'); ?>
                </div>
            </div>



    </div>

    <?php $theView->hiddenInput('template[id]')->setValue($tplId); ?>

    <script>
    fpcm.templates.createEditorInstance('<?php print $tplId; ?>');
    </script>
</div>
