<div class="row">
    <div class="col-12 px-0">
        <?php $theView->textarea('article[content]')->setClass('fpcm-ui-full-width')->setValue(stripslashes($article->getContent())); ?>
    </div>
</div>