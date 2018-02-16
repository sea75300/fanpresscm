<div class="fpcm-ui-controlgroup">
<?php foreach ($categories as $value => $key) : ?>
    <?php $selected = in_array($value, $article->getCategories()); ?>
    <?php $theView->checkbox($fieldname, 'cat'.$value)->setValue($value)->setText($key->getName())->setSelected($selected); ?>
<?php endforeach; ?>
</div>