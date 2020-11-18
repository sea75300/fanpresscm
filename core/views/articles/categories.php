<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="col-12 px-0">
    <?php $theView->select('article[categories][]')->setIsMultiple(true)->setOptions($categories)->setSelected($article->getCategories()); ?>
</div>