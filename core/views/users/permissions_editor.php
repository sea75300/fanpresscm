<?php /* @var $theView \fpcm\view\viewVars */ ?>  
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
    <div class="col">
        <div class="list-group my-2">
            <div class="list-group-item bg-secondary text-white"><?php $theView->icon('book'); ?> <?php $theView->write('PERMISSION_ARTICLES'); ?></div>
            <?php foreach ($permissions['article'] as $key => $value) : ?>
                <div class="list-group-item">
                    <?php $theView->checkbox("permissions[article][{$key}]", "{$rollId}_article_{$key}")
                        ->setText('PERMISSION_ARTICLE_' . strtoupper($key))
                        ->setSelected($value)
                        ->setSwitch(true)
                        ->setWrapperClass('text-truncate'); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col">
        <div class="list-group my-2">
            <div class="list-group-item bg-secondary text-white"><?php $theView->icon('comments'); ?> <?php $theView->write('PERMISSION_COMMENTS'); ?></div>
            <?php foreach ($permissions['comment'] as $key => $value) : ?>
                <div class="list-group-item">
                    <?php $theView->checkbox("permissions[comment][{$key}]", "{$rollId}_comment_{$key}")
                        ->setText('PERMISSION_COMMENT_' . strtoupper($key))
                        ->setSelected($value)
                        ->setSwitch(true)
                        ->setWrapperClass('text-truncate'); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col">
        <div class="list-group my-2">
            <div class="list-group-item bg-secondary text-white"><?php $theView->icon('folder-open'); ?> <?php $theView->write('PERMISSION_UPLOADS'); ?></div>
            <?php foreach ($permissions['uploads'] as $key => $value) : ?>
                <div class="list-group-item">
                    <?php $theView->checkbox("permissions[uploads][{$key}]", "{$rollId}_uploads_{$key}")
                        ->setText('PERMISSION_UPLOADS_' . strtoupper($key))
                        ->setSelected($value)
                        ->setSwitch(true)
                        ->setWrapperClass('text-truncate'); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col">
        <div class="list-group my-2">
            <div class="list-group-item bg-secondary text-white"><?php $theView->icon('cog'); ?> <?php $theView->write('PERMISSION_SYSTEM'); ?></div>
            <?php foreach ($permissions['system'] as $key => $value) : ?>
                <div class="list-group-item">
                <?php $readOnly = (in_array($key, ['permissions', 'users', 'rolls']) && $rollId == 1) ? true : false; ?>
                <?php $theView->checkbox("permissions[system][{$key}]", "{$rollId}_system_{$key}")
                    ->setText('PERMISSION_SYSTEM_' . strtoupper($key))
                    ->setSelected($value)
                    ->setReadonly($readOnly)
                    ->setSwitch(true)
                        ->setWrapperClass('text-truncate'); ?>
                <?php if ($readOnly) : ?><?php $theView->hiddenInput("permissions[system][{$key}]"); ?><?php endif; ?>
                </div>
            <?php endforeach; ?>           
        </div>
    </div>
    
    <div class="col">
        <div class="list-group my-2">
            <div class="list-group-item bg-secondary text-white"><?php $theView->icon('plug'); ?> <?php $theView->write('PERMISSION_MODULES'); ?></div>
            <?php foreach ($permissions['modules'] as $key => $value) : ?>
                <div class="list-group-item">
                <?php $theView->checkbox("permissions[modules][{$key}]", "{$rollId}_modules_{$key}")
                    ->setText('PERMISSION_MODULES_' . strtoupper($key))
                    ->setSelected($value)
                    ->setSwitch(true)
                    ->setWrapperClass('text-truncate'); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>