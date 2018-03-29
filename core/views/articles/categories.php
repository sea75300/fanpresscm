<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="col-12 fpcm-ui-padding-none-lr">
    <fieldset class="fpcm-ui-marginleft-none fpcm-ui-marginright-none">
        <legend><?php $theView->write('HL_CATEGORIES_MNG'); ?></legend>

        <div class="fpcm-ui-controlgroup">
        <?php foreach ($categories as $value => $key) : ?>
            <?php $selected = in_array($value, $article->getCategories()); ?>
            <?php $theView->checkbox($fieldname, 'cat'.$value)->setValue($value)->setText($key->getName())->setSelected($selected); ?>
        <?php endforeach; ?>
        </div>   
    </fieldset>
</div>

