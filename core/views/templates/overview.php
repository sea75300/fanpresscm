<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($theView->permissions->system->drafts) : ?>
<?php include $uploadTemplatePath; ?>
<div id="fpcm-dataview-draftfiles"></div>
<?php endif; ?>