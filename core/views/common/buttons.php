<?php if ($theView->buttons) : ?>
<div class="fpcm-ui-background-white-50p" id="fpcm-ui-toolbar">
    <div class="fpcm-ui-toolbar">
        <?php foreach ($theView->buttons as $button) : ?><?php print $button; ?><?php endforeach; ?>
    </div>
</div>
 <?php endif; ?>