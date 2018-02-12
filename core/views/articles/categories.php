<div class="fpcm-ui-toolbar">
<?php foreach ($categories as $value => $key) : ?>
    <?php $selected = in_array($value, $article->getCategories()); ?>
    <?php $theView->checkbox('article[categories][]', 'cat'.$value)->setValue($value)->setText($key->getName())->setSelected($selected); ?>
<?php endforeach; ?>
</div>