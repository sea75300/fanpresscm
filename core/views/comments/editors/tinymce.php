<div class="row py-2">
    <?php $theView->textarea('comment[text]', 'commenttext')->setValue(stripslashes($comment->getText()) ); ?>
</div>