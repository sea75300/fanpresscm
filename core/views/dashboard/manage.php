<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php /* @var $container \fpcm\model\abstracts\dashcontainer */ ?>
<ul id="fpcm-ui-container-disabled-list" class="list-group">
<?php if (!count($disabledContainer)) : ?>
    <li class="list-group-item"><?php $theView->icon('images', 'far')->setStack('ban text-danger')->setSize('lg')->setStackTop(true); ?> <?php $theView->write('GLOBAL_NOTFOUND2'); ?></li>
<?php else : ?>    
    <?php foreach ($disabledContainer as $container) : ?>
        <li class="list-group-item" data-container="<?php print base64_encode($container::class); ?>"><?php $theView->write($container->getHeadline()); ?></li>
    <?php endforeach; ?>    
<?php endif; ?>        
</ul>