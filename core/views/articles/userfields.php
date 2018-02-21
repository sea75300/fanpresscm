<?php if (count($userfields)) : ?>
    <?php foreach ($userfields as $options) : ?>
        <div class="fpcm-ui-editor-extended-row">
            <div class="fpcm-ui-editor-extended-icon"><?php print $options->getIcon(); ?></div>
            <div class="fpcm-ui-editor-extended-button">                
                <?php print $options; ?>
            </div>
            <div class="fpcm-ui-editor-extended-col">

            </div>
            <div class="fpcm-ui-clear"></div>
        </div>  
    <?php endforeach; ?>
<?php endif; ?>