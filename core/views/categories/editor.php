<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-padding-md-tb">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
            <legend><?php $theView->write('CATEGORIES_EDIT'); ?></legend>
            
            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12">
                    <div class="row">
                        <?php $theView->textInput('category[name]')
                            ->setValue($category->getName())
                            ->setWrapper(false)
                            ->setText('CATEGORIES_NAME')
                            ->setIcon('tag')
                            ->setDisplaySizesDefault(); ?>
                    </div>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12">
                    <div class="row">
                        <?php $theView->textInput('category[iconpath]')
                            ->setValue($category->getIconPath())
                            ->setType('url')
                            ->setWrapper(false)
                            ->setText('CATEGORIES_ICON_PATH')
                            ->setIcon('link')
                            ->setDisplaySizesDefault(); ?>
                    </div>
                </div>
            </div>

            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-12 fpcm-ui-padding-none-lr">
                    <div class="row">
                        <label class="col-12 col-sm-6 col-md-3 fpcm-ui-field-label-general">
                            <?php $theView->icon('users'); ?>
                            <?php $theView->write('CATEGORIES_ROLLS'); ?>:
                        </label>
                        <div class="col-12 col-sm-6 col-md-9 fpcm-ui-padding-none-lr">
                            <div class="fpcm-ui-controlgroup fpcm-ui-borderradius-remove-left">
                            <?php foreach ($userRolls as $rollname => $rollid) : ?>
                                <?php $theView->checkbox('category[groups][]', 'cat'.$rollid)->setText($rollname)->setValue($rollid)->setSelected(isset($selectedGroups) && in_array($rollid, $selectedGroups) ? true : false); ?>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
