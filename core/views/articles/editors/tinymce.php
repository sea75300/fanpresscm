<div class="row">
    <div class="col-12 mb-2">
        <?php $theView->textarea('article[content]')->setClass('fpcm-ui-full-width')->setValue(stripslashes($article->getContent())); ?>
    </div>
</div>