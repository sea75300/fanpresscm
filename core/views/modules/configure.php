<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general" id="tabs-module-<?php print $prefix; ?>-config">
        <ul>
            <li><a href="#tabs-config-<?php print $prefix; ?>"><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></a></li>
        </ul>            

        <div id="tabs-config-<?php print $prefix; ?>">
        <?php foreach ($fields as $field => $conf) : ?>
            <?php $field = $theView->{$conf['type']}('config['.$field.']'); ?>
            <?php if ($field instanceof \fpcm\view\helper\select) : ?>
                <label class="col-12 col-sm-6 col-md-3 fpcm-ui-field-label-general">
                    <?php $theView->icon($conf['icon']); ?>
                    <?php $theView->write($conf['label']); ?>:
                </label>
                <div class="col-auto fpcm-ui-padding-none-lr">
                    <?php $field->setSelected($options[$field])->setOptions($conf['options'])->setFirstOption($conf['first']); ?>
                </div>
            <?php else : ?>
                <?php $field->setValue($options[$field])->setText($conf['label'])->setIcon($conf['icon'])->setSize('lg'); ?>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    </div>
</div>