<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row fpcm-ui-padding-md-tb no-gutters">
    <div class="col-12 col-md-6">
        <div class="row">
            <?php $theView->textInput('category[name]')
                ->setValue($category->getName())
                ->setWrapper(false)
                ->setText('CATEGORIES_NAME')
                ->setIcon('tag')
                ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
        </div>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb no-gutters">
    <div class="col-12 col-md-6">
        <div class="row">
            <?php $theView->textInput('category[iconpath]')
                ->setValue($category->getIconPath())
                ->setType('url')
                ->setWrapper(false)
                ->setText('CATEGORIES_ICON_PATH')
                ->setIcon('link')
                ->setClass('col-12 col-md-7 fpcm-ui-field-input-nowrapper-general')
                ->setLabelClass('col-12 col-md-5 fpcm-ui-field-label-general'); ?>
        </div>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-12 col-md-6 fpcm-ui-padding-none-lr">
        <div class="row">
            <label class="col-12 col-md-5 fpcm-ui-field-label-general">
                <?php $theView->icon('users'); ?>
                <?php $theView->write('CATEGORIES_ROLLS'); ?>:
            </label>
            <div class="col-12 col-md-auto fpcm-ui-padding-none-lr">
                <div class="fpcm-ui-controlgroup">
                <?php foreach ($userRolls as $rollname => $rollid) : ?>
                    <?php $theView->checkbox('category[groups][]', 'cat'.$rollid)->setText($rollname)->setValue($rollid)->setSelected(isset($selectedGroups) && in_array($rollid, $selectedGroups) ? true : false); ?>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>