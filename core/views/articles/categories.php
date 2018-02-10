<div class="fpcm-ui-toolbar">
<?php foreach ($categories as $value => $key) : ?>
    <?php $selected = in_array($value, $article->getCategories()); ?>
    <?php fpcm\view\helper::checkbox('article[categories][]', '', $value, $key->getName(), 'cat'.$value, $selected); ?>
<?php endforeach; ?>
</div>