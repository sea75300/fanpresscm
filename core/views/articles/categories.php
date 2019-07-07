<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="col-12 fpcm-ui-padding-none-lr">


        <select name="article[categories][]" id="articlecategories" multiple>
        <?php foreach ($categories as $value => $key) : ?>
            <?php $selected = in_array($value, $article->getCategories()); ?>
            
            <option value="<?php print $value; ?>" <?php if ($selected) : ?>selected<?php endif; ?>><?php print $theView->escape($key->getName()); ?></option>
            
        <?php endforeach; ?>            
    </select>

</div>

