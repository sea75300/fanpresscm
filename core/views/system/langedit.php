<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php foreach ($langVars as $key => $value) : ?>

    <div class="row py-2">
        <?php if (is_array($value)) : ?>
            <?php $theView->textInput('lang['.$key.']')->setText('  '.$key)->setValue(json_encode($value))->setDisplaySizesDefault()->setMaxlenght(2048);  ?>
        <?php else : ?>
            <?php $theView->textInput('lang['.$key.']')->setText('  '.$key)->setValue($value)->setDisplaySizesDefault()->setMaxlenght(512);  ?>
        <?php endif; ?>
    </div>

<?php endforeach; ?>