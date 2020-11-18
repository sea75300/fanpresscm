<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-massedit-dialog" id="fpcm-dialog-articles-massedit">   
    <?php fpcm\components\components::getMassEditFields($masseditFields); ?>
    
    <div class="row fpcm-ui-padding-md-tb">
        <label class="col-12 col-md-4 fpcm-ui-field-label-general">
            <?php $theView->icon('tags')->setSize('lg'); ?>
            <?php $theView->write('TEMPLATE_ARTICLE_CATEGORYTEXTS'); ?>
        </label>
        
        <div class="col-12 col-md-8 px-0 fpcm-ui-editor-categories fpcm-ui-editor-categories-massedit">
            <?php $theView->select('categories[]')->setIsMultiple(true)->setOptions($massEditCategories)->setSelected([]); ?>
        </div>
    </div>
</div>