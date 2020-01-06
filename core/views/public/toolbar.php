<?php /* @var $theView fpcm\view\viewVars  */ ?>
<?php if ($theView->loggedIn) : ?>
<div class="fpcm-pub-articletoolbar-main">
    <?php if ($theView->permissions->article->add) : ?>
    <a target="_blank" href="<?php print $theView->basePath; ?>articles/add"><?php $theView->write('HL_ARTICLE_ADD'); ?></a> &bull;
    <?php endif; ?>

    <?php if ($theView->permissions->editArticles()) : ?>
    <a target="_blank" href="<?php print $theView->basePath; ?>articles/listactive"><?php $theView->write('HL_ARTICLE_EDIT_ACTIVE'); ?></a> &bull;
    <?php endif; ?>

    <a href="<?php print $theView->basePath; ?>system/logout&redirect=1"><?php $theView->write('LOGOUT_BTN'); ?></a>    
</div>
<?php endif; ?>