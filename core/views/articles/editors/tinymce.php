<div class="row fpcm-ui-padding-md-tb">
    <div class="col-12 fpcm-ui-padding-none-lr">
        <?php $theView->textarea('article[content]')->setClass('fpcm-ui-full-width')->setValue(stripslashes($article->getContent())); ?>
    </div>
</div>