<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-massedit-dialog" id="fpcm-dialog-articles-massedit">
    
    <div class="row fpcm-ui-padding-md-tb">
        <div class="col-1 fpcm-ui-padding-none-lr align-self-center"><?php $theView->icon('tags')->setSize('lg'); ?></div>
        <div class="col-3 fpcm-ui-padding-none-lr align-self-center"><?php $theView->write('TEMPLATE_ARTICLE_CATEGORYTEXTS'); ?></div>
        <div class="col-8 align-self-center">
            <div class="fpcm-ui-massedit-categories">
                <div class="fpcm-ui-controlgroup">
                    <?php foreach ($massEditCategories as $name => $id) : ?>
                    <?php $theView->checkbox('categories[]', 'cat'.$id)->setClass('fpcm-ui-input-massedit-categories')->setText($name)->setValue($id); ?>
                    <?php endforeach; ?>
                </div>
            </div>        
        </div>
    </div>
    
    <?php fpcm\components\components::getMassEditFields($masseditFields); ?>
</div>