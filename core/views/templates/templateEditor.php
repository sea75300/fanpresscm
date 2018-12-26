<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if (!$isWritable) : ?>
<div class="row no-gutters fpcm-ui-padding-md-tb">
    <div class="col-5 col-md-1 align-self-center"><?php $theView->icon('lock ')->setSize('2x'); ?></div>
    <div class="col-7 col-md-11 align-self-center">
        <?php $theView->write('TEMPLATE_NOT_WRITABLE'); ?>
    </div>
</div>
<?php endif; ?>
<div class="row no-gutters fpcm-ui-padding-md-tb">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
            <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
            <?php $theView->write('TEMPLATE_NOTES'); ?>
        </fieldset>
    </div>
</div>
<div class="row no-gutters fpcm-ui-padding-md-tb">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
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
</div>

<div class="row no-gutters fpcm-ui-padding-md-bottom">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
            <legend><?php $theView->write('GLOBAL_HTMLTAGS_ALLOWED'); ?></legend>

            <p class="fpcm-ui-monospace fpcm-ui-margin-none"><?php print $allowedTags; ?> </p>
        </fieldset>
    </div>
</div>

<div class="row no-gutters fpcm-ui-padding-md-tb">
    <div class="col-12">
        <?php $theView->textarea('template[content]', 'content_'.$tplId)->setValue($content, ENT_QUOTES)->setClass('fpcm-ui-template-textarea'); ?>
    </div>
</div>

<?php $theView->hiddenInput('template[id]')->setValue($tplId); ?>