<?php /* @var $theView \fpcm\view\viewVars */ ?>

<div class="row">
    <div class="col-12 col-lg-9 px-0">
        <div id="fpcm-dataview-userlist"></div>
    </div>

    <div class="col-12 col-lg-3 d-none d-lg-block">
        <?php print $userArticles; ?>
    </div>
</div>


<?php include $theView->getIncludePath('users/userlist_dialogs.php'); ?>