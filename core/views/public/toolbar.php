<div class="fpcm-pub-articletoolbar-main">
    <?php if ($permAdd) : ?>
    <a target="_blank" href="<?php print $FPCM_BASEMODULELINK; ?>articles/add"><?php $FPCM_LANG->write('HL_ARTICLE_ADD'); ?></a> &bull;
    <?php endif; ?>

    <?php if ($permEditOwn || $permEditAll) : ?>
    <a target="_blank" href="<?php print $FPCM_BASEMODULELINK; ?>articles/listactive"><?php $FPCM_LANG->write('HL_ARTICLE_EDIT_ACTIVE'); ?></a> &bull;
    <?php endif; ?>

    <a href="<?php print $FPCM_BASEMODULELINK; ?>system/logout&redirect=1"><?php $FPCM_LANG->write('LOGOUT_BTN'); ?></a>    
</div>

