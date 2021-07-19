<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row">
    <div class="col-12 col-md-6">
        <fieldset class="my-3">
            
            <div class="row">
                <?php $theView->textInput('category[name]')
                    ->setValue($category->getName())
                    ->setAutoFocused(true)
                    ->setText('CATEGORIES_NAME')
                    ->setIcon('tag'); ?>
            </div>

            <div class="row">
                <?php $theView->textInput('category[iconpath]')
                    ->setValue($category->getIconPath())
                    ->setType('url')
                    ->setText('CATEGORIES_ICON_PATH')
                    ->setIcon('link'); ?>
            </div>


            <div class="row">
                
                <div class="col-form-label col-12 col-sm-6 col-md-3">
                    <?php $theView->icon('users'); ?> <span class="fpcm-ui-label ps-1"> <?php $theView->write('CATEGORIES_ROLLS'); ?></span>
                </div>
                
                <div class=" col-12 col-sm-6 col-md-9">
                    <?php foreach ($userRolls as $rollname => $rollid) : ?>
                        <?php $theView->checkbox('category[groups][]', 'cat'.$rollid)
                                ->setText($rollname)
                                ->setValue($rollid)
                                ->setSwitch(true)
                                ->setSelected(isset($selectedGroups) && in_array($rollid, $selectedGroups)); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>
