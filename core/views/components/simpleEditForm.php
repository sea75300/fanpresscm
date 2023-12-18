<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($theView->debugMode) : ?>
<div class="d-flex justify-content-end text-secondary fs-6 p-2">Form: <?php $theView->escape($formFields->area); ?></div>
<?php endif; ?>

<div class="row row-cols-1 row-cols-md-2">
    <div class="col">
        <fieldset class="my-3">            
        <?php foreach ($formFields->fields as $field) : ?>
            <div class="row g-0">
                <?php if ($field instanceof \fpcm\components\fieldGroup) : ?>
                    <div class="col">
                        <div class="list-group">
                            <div class="list-group-item bg-secondary text-white" aria-label="<?php $theView->write($field->getDescr()); ?>">
                                <?php if ($field->getIcon() !== null) : print $field->getIcon(); endif; ?> 
                                <span class="fpcm-ui-label ps-1"> <?php $theView->write($field->getDescr()); ?></span>
                            </div>                            
                            
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
