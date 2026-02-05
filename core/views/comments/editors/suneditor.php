<div class="row">
    <div class="col-12 mb-2">
        <?php $theView->textarea('comment[text]', 'suneditor')->setClass('w-100')->setValue(stripslashes($article->getContent())); ?>
    </div>
</div>