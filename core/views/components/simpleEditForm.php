<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row">
    <div class="col-12 col-md-6">
        <fieldset class="my-3">
            
        <?php foreach ($formFields as $field) : ?>
            <div class="row">
                <?php if ($field instanceof \fpcm\components\fieldGroup) : ?>
                    <div class="col-form-label col-12 col-sm-6 col-md-4">
                        <?php if ($field->getIcon() !== null) : print $field->getIcon(); endif; ?> 
                        <span class="fpcm-ui-label ps-1"> <?php $theView->write($field->getDescr()); ?></span>
                    </div>

                    <div class="col">
                    <?php foreach ($field->getFields() as $subField) : ?>
                        <?php print $subField; ?>
                    <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <?php print $field; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </fieldset>
    </div>
</div>
