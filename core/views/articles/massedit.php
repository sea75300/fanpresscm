<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm ui-hidden" id="fpcm-dialog-articles-massedit">   
    <?php fpcm\components\components::getMassEditFields($masseditFields); ?>
    
    <div class="row py-2">
         <label class="col-12 col-md-4">
             <?php $theView->icon('tags')->setSize('lg'); ?>
             <?php $theView->write('TEMPLATE_ARTICLE_CATEGORYTEXTS'); ?>
         </label>

         <div class="col-12 col-md-8 px-0">
             <div class="fpcm-ui-editor-categories">
                <?php $theView->select('categories[]')->setIsMultiple(true)->setOptions($massEditCategories)->setSelected([]); ?>
             </div>
         </div>
     </div>
</div>