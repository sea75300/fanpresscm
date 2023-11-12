<div class="row g-0 p-3">
    <?php $theView->textarea('comment[text]', 'commenttext')->setValue(stripslashes($comment->getText()) ); ?>
</div>