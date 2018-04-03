<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('CATEGORIES_NAME'); ?>
    </div>
    <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('category[name]')->setValue($category->getName()); ?>
    </div>
</div>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('CATEGORIES_ICON_PATH'); ?>
    </div>
    <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
        <?php $theView->textInput('category[iconpath]')->setValue($category->getIconPath()); ?>
    </div>
</div>
<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
        <?php $theView->write('CATEGORIES_ROLLS'); ?>
    </div>
    <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
        <div class="fpcm-ui-controlgroup">
        <?php foreach ($userRolls as $rollname => $rollid) : ?>
            <?php $theView->checkbox('category[groups][]', 'cat'.$rollid)->setText($rollname)->setValue($rollid)->setSelected(isset($selectedGroups) && in_array($rollid, $selectedGroups) ? true : false); ?>
        <?php endforeach; ?>
        </div>
    </div>
</div>