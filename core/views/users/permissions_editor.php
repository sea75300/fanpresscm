<?php /* @var $theView \fpcm\view\viewVars */ ?>  
<fieldset class="mb-2">
    <legend><?php $theView->write('PERMISSION_ARTICLES'); ?></legend>
    <?php foreach ($permissions['article'] as $key => $value) : ?>
        <div class="row">
            <div class="col">
                <?php $theView->checkbox("permissions[article][{$key}]", "{$rollId}_article_{$key}")->setText('PERMISSION_ARTICLE_' . strtoupper($key))->setSelected($value)->setSwitch(true); ?>
            </div>
        </div>
    <?php endforeach; ?>
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('PERMISSION_COMMENTS'); ?></legend>
    <?php foreach ($permissions['comment'] as $key => $value) : ?>
        <div class="row">
            <div class="col">
                <?php $theView->checkbox("permissions[comment][{$key}]", "{$rollId}_comment_{$key}")->setText('PERMISSION_COMMENT_' . strtoupper($key))->setSelected($value)->setSwitch(true); ?>
            </div>
        </div>
    <?php endforeach; ?>
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('PERMISSION_UPLOADS'); ?></legend>
    <?php foreach ($permissions['uploads'] as $key => $value) : ?>
        <div class="row">
            <div class="col">
                <?php $theView->checkbox("permissions[uploads][{$key}]", "{$rollId}_uploads_{$key}")->setText('PERMISSION_UPLOADS_' . strtoupper($key))->setSelected($value)->setSwitch(true); ?>
            </div>
        </div>
    <?php endforeach; ?>
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('PERMISSION_SYSTEM'); ?></legend>
    <?php foreach ($permissions['system'] as $key => $value) : ?>
        <div class="row">
            <div class="col">
                <?php $readOnly = (in_array($key, ['permissions', 'users', 'rolls']) && $rollId == 1) ? true : false; ?>
                <?php $theView->checkbox("permissions[system][{$key}]", "{$rollId}_system_{$key}")->setText('PERMISSION_SYSTEM_' . strtoupper($key))->setSelected($value)->setReadonly($readOnly)->setSwitch(true); ?>
                <?php if ($readOnly) : ?><?php $theView->hiddenInput("permissions[system][{$key}]"); ?><?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('PERMISSION_MODULES'); ?></legend>
    <?php foreach ($permissions['modules'] as $key => $value) : ?>
        <div class="row">
            <div class="col">
                <?php $theView->checkbox("permissions[modules][{$key}]", "{$rollId}_modules_{$key}")->setText('PERMISSION_MODULES_' . strtoupper($key))->setSelected($value)->setSwitch(true); ?>
            </div>
        </div>
    <?php endforeach; ?>
</fieldset>