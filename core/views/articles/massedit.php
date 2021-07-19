<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-massedit-dialog" id="fpcm-dialog-articles-massedit">   
    <?php fpcm\components\components::getMassEditFields($masseditFields); ?>
    
    <div class="row py-2">
        <?php $theView->select('categories[]')
            ->setIsMultiple(true)
            ->setOptions($massEditCategories)
            ->setSelected([])
            ->setIcon('tags')
            ->setText('TEMPLATE_ARTICLE_CATEGORYTEXTS'); ?>
    </div>
</div>