<div class="row fpcm-ui-padding-md-tb" style="font-size: <?php print $editorDefaultFontsize; ?>">
    <?php $theView->textarea('article[content]')->setClass('fpcm-full-width')->setValue(stripslashes($article->getContent())); ?>
</div>