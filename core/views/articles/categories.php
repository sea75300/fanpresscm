<div class="fpcm-ui-buttonset">
<?php foreach ($categories as $value => $key) : ?>
    <?php $selected = in_array($value, $article->getCategories()); ?>
    <?php fpcm\model\view\helper::checkbox('article[categories][]', '', $value, $key->getName(), 'cat'.$value, $selected); ?>
<?php endforeach; ?>
</div>