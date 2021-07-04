<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div id="fpcm-dataview-articlelist"></div>

<?php if ($includeSearchForm) : ?><?php include $theView->getIncludePath('articles/searchform.php'); ?><?php endif; ?>
<?php if ($includeMassEditForm && $theView->permissions->editArticlesMass()) : ?><?php include $theView->getIncludePath('articles/massedit.php'); ?><?php endif; ?>