<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row border-top border-5 border-primary">
    <div class="col-12 col-lg-8 col-xl-6">
        <fieldset class="my-3">
            
        <?php foreach ($formFields as $field) : ?>
            <div class="row">
                <?php if ($field instanceof \fpcm\components\fieldGroup) : ?>
                    <div class="col-form-label col-12 col-sm-6 col-md-4">
                        <?php if ($field->getIcon() !== null) : print $field->getIcon(); endif; ?> 
                        <span class="fpcm-ui-label ps-1"> <?php $theView->write($field->getDescr()); ?></span>
                    </div>

                    <div class="col ps-sm-0">
                        <div class="list-group">
                    <?php foreach ($field->getFields() as $subField) : ?>
                            <div class="list-group-item">
                            <?php print $subField; ?>
                            </div>
                    <?php endforeach; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <?php print $field; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </fieldset>
    </div>
</div>
