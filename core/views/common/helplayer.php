<div class="offcanvas offcanvas-end" id="fpcm-offcanvas-help" data-bs-scroll="true" tabindex="-1" aria-labelledby="fpcm-help-open-title" data-ref="<?php print $theView->helpLink['ref']; ?>" data-chapter="<?php print $theView->helpLink['chapter']; ?>">
    <div class="offcanvas-header">
        <h3 id="fpcm-help-open-title"><?php $theView->write('HL_HELP'); ?></h3>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php $theView->write('GLOBAL_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-header offcanvas-nav d-none"></div>
    <div class="offcanvas-body" data-bs-spy="scroll"></div>
</div>
