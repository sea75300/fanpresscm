<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-padding-md-tb">
    <div class="col-12">
        <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
            <legend><?php $theView->write('CATEGORIES_EDIT'); ?></legend>
            
            <div class="row no-gutters py-2">
                <div class="col-12">
                    <div class="row">
                        <?php $theView->textInput('category[name]')
                            ->setValue($category->getName())
                            ->setAutoFocused(true)
                            ->setText('CATEGORIES_NAME')
                            ->setIcon('tag'); ?>
                    </div>
                </div>
            </div>

            <div class="row no-gutters py-2">
                <div class="col-12">
                    <div class="row">
                        <?php $theView->textInput('category[iconpath]')
                            ->setValue($category->getIconPath())
                            ->setType('url')
                            ->setText('CATEGORIES_ICON_PATH')
                            ->setIcon('link'); ?>
                    </div>
                </div>
            </div>

            <div class="row py-2">
                <div class="col-12 px-0">
                    <div class="row">
                        <label class="col-12 col-sm-6 col-md-3 fpcm-ui-field-label-general">
                            <?php $theView->icon('users'); ?>
                            <?php $theView->write('CATEGORIES_ROLLS'); ?>:
                        </label>
                        <div class="col-12 col-sm-6 col-md-9 fpcm ui-element-min-height-md fpcm-ui-input-wrapper-inner fpcm-ui-border-grey-medium fpcm-ui-border-radius-all">
                            <?php foreach ($userRolls as $rollname => $rollid) : ?>
                                <?php $theView->checkbox('category[groups][]', 'cat'.$rollid)
                                        ->setLabelClass('mr-2')
                                        ->setText($rollname)
                                        ->setValue($rollid)
                                        ->setSelected(isset($selectedGroups) && in_array($rollid, $selectedGroups) ? true : false); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
