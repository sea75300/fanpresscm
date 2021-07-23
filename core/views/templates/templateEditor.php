<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if (!$isWritable) : ?>
<div class="row py-2">
    <div class="col-auto align-self-center"><?php $theView->icon('lock')->setSize('2x')->setClass('text-danger'); ?></div>
    <div class="col-auto align-self-center"><?php $theView->write('TEMPLATE_NOT_WRITABLE'); ?></div>
</div>
<?php endif; ?>

<div class="row g-0 my-2">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
            <?php $theView->write('TEMPLATE_NOTES'); ?>
        </fieldset>
    </div>
</div>

<div class="row g-0 mt-3">
    
    <div class="col-12 col-md-6 col-lg-3">
        <fieldset>
            <legend><?php $theView->write('TEMPLATE_REPLACEMENTS'); ?></legend>

            <div class="m-2">
                <dl class="fpcm-ui-monospace">
                <?php foreach ($replacements as $tag => $descr) : ?>
                    <dt class="pb-2">
                        <?php $theView->button('in'. trim('$tag', '{}'))
                                ->setText($descr)
                                ->setIconOnly(true)
                                ->setIcon('plus-square')
                                ->setSize('lg')
                                ->setClass('fpcm-ui-template-tags')
                                ->setData(['tag' => $tag]); ?>
                        <?php print $tag; ?>
                    </dt>
                    <dd<?php if (!isset($attributes[$tag])) : ?> class="pb-2"<?php endif; ?>><?php print $descr; ?></dd>
                    <?php if (isset($attributes[$tag])) : ?>
                    <dd class="fpcm-ui-font-small pb-2"><?php $theView->write('TEMPLATE_ATTRIBUTES') ?>: <?php print implode(', ', $attributes[$tag]); ?></dd>
                    <?php endif; ?>
                <?php endforeach; ?>
                </dl>
            </div>
        </fieldset>
    </div>

    <div class="col-12 col-md-6 col-lg-9">
        <?php if (count($allowedTagsList)) : ?>
        <div class="row">
            <div class="col-12 mb-2 pe-0 overflow-auto">
                <fieldset>
                    <legend><?php $theView->write('TEMPLATE_EDITOR'); ?></legend>
                    
                    <?php foreach ($allowedTagsList as $allowedTags) : ?>
                        <div class="btn-group mb-1" role="group" aria-label="<?php $theView->write('TEMPLATE_EDITOR'); ?>">
                        <?php foreach ($allowedTags as $tag) : ?>
                            <?php $theView->button('tps-editor-'.substr($tag, 1, -1))->setText(htmlentities($tag))->setClass('fpcm-editor-html-click')->setData(['htmltag' => substr($tag, 1, -1)]); ?>
                        <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                </fieldset>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-12">
                <?php $theView->textarea('template[content]', 'content_'.$tplId)->setValue($content, ENT_QUOTES)->setClass('fpcm-editor-html-click'); ?>
            </div>
        </div>
    </div>
    
</div>

<?php $theView->hiddenInput('template[id]')->setValue($tplId); ?>