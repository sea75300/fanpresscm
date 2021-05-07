<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if (!$isWritable) : ?>
<div class="row g-0 fpcm-ui-padding-md-tb">
    <div class="col-5 col-md-1 align-self-center"><?php $theView->icon('lock ')->setSize('2x'); ?></div>
    <div class="col-7 col-md-11 align-self-center">
        <?php $theView->write('TEMPLATE_NOT_WRITABLE'); ?>
    </div>
</div>
<?php endif; ?>

<div class="row g-0 mt-2 mb-3">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
            <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
            <?php $theView->write('TEMPLATE_NOTES'); ?>
        </fieldset>
    </div>
</div>

<div class="row g-0 mt-3">
    
    <div class="col-12 col-md-6 col-lg-5 col-xl-3 mb-3 mb-md-0">
        <fieldset class="ms-0 me-md-3 fpcm-ui-full-height">
            <legend><?php $theView->write('TEMPLATE_REPLACEMENTS'); ?></legend>

            <dl class="fpcm-ui-monospace">
            <?php foreach ($replacements as $tag => $descr) : ?>
                <dt><a href="#" data-tag="<?php print $tag; ?>" class="fpcm-ui-template-tags fpcm-ui-float-left fpcm-ui-block fpcm-ui-padding-md-right"><?php $theView->icon('plus-square ')->setSize('lg'); ?></a> <?php print $tag; ?></dt>
                <dd<?php if (!isset($attributes[$tag])) : ?> class="fpcm-ui-padding-md-bottom"<?php endif; ?>><?php print $descr; ?></dd>
                <?php if (isset($attributes[$tag])) : ?>
                <dd class="fpcm-ui-padding-md-bottom fpcm-ui-font-small"><?php $theView->write('TEMPLATE_ATTRIBUTES') ?>: <?php print implode(', ', $attributes[$tag]); ?></dd>
                <?php endif; ?>
            <?php endforeach; ?>
            </dl>
        </fieldset>
    </div>

    <div class="col-12 col-md-6 col-lg-7 col-xl-9">
        <?php if (count($allowedTags)) : ?>
        <div class="row g-0">
            <div class="col-12 mb-2">
                <fieldset>
                    <legend><?php $theView->write('TEMPLATE_EDITOR'); ?></legend>
                    
                    <div class="fpcm-ui-controlgroup">
                    <?php foreach ($allowedTags as $tag) : ?>
                        <?php $theView->button('tps-editor-'.substr($tag, 1, -1))->setText(htmlentities($tag))->setClass('fpcm-editor-html-click')->setData(['htmltag' => substr($tag, 1, -1)]); ?>
                    <?php endforeach; ?>
                    </div>
                </fieldset>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="row g-0">
            <div class="col-12">
                <?php $theView->textarea('template[content]', 'content_'.$tplId)->setValue($content, ENT_QUOTES)->setClass('fpcm-editor-html-click'); ?>
            </div>
        </div>
    </div>
    
</div>

<?php $theView->hiddenInput('template[id]')->setValue($tplId); ?>