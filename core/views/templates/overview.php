<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if ($theView->permissions->system->drafts) : ?>
<div class="border-top border-5 border-primary">
    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-md-6 mt-3">
            <?php include $uploadTemplatePath; ?>
        </div>
    </div>
</div>

<div id="fpcm-dataview-draftfiles"></div>
<?php endif; ?>