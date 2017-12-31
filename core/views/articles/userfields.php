<?php if (count($userfields)) : ?>
    <?php foreach ($userfields as $options) : ?>
        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><span class="fa fa-<?php print $options['type']; ?> fa-fw fa-lg"></span></div>
            <div class="fpcm-ui-editor-extended-button">
            <?php if ($options['type'] == 'textarea') : ?>
                <p><?php print $options['description']; ?>:</p>
                <?php \fpcm\model\view\helper::textArea('userfields['.$options['name'].']', $options['class'], $options['value'], $options['readonly']) ?>
            <?php elseif ($options['type'] == 'select') : ?>
                <p><?php print $options['description']; ?>:</p>
                <?php \fpcm\model\view\helper::select('userfields['.$options['name'].']', $options['options'], $options['value'], $options['firstempty'], $options['firstenabled'], $options['readonly'], $options['class']) ?>
            <?php elseif ($options['type'] == 'checkbox') : ?>
                <?php \fpcm\model\view\helper::checkbox('userfields['.$options['name'].']', $options['class'], $options['value'], $options['description'], $options['id'], $options['selected'], $options['readonly']) ?>
            <?php elseif ($options['type'] == 'radio') : ?>
                <?php \fpcm\model\view\helper::radio('userfields['.$options['name'].']', $options['class'], $options['value'], $options['description'], $options['id'], $options['selected'], $options['readonly']) ?>
            <?php else: ?>
                <?php \fpcm\model\view\helper::textInput('userfields['.$options['name'].']', $options['class'], $options['value'], $options['readonly'], $options['lenght']) ?>
            <?php endif; ?>    
            </div>
            <div class="fpcm-ui-editor-extended-col">

            </div>
            <div class="fpcm-clear"></div>
        </div>  
    <?php endforeach; ?>
<?php endif; ?>