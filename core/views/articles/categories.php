<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="col-12 fpcm-ui-padding-none-lr">
    <?php $theView->select('article[categories][]')->setIsMultiple(true)->setOptions($categories)->setSelected($article->getCategories()); ?>
</div>