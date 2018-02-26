    <form method="post" action="<?php print $file->getEditLink(); ?>" enctype="multipart/form-data">
        <table class="fpcm-ui-table fpcm-ui-articletemplates">
            <tr>
                <td><?php $theView->textarea('templatecode', 'templatecode')->setValue($file->getContent()); ?></td>
            </tr>  
        </table>

        <div class="fpcm-ui-hidden"><?php $theView->saveButton('saveTemplate'); ?></div>

        <?php $theView->pageTokenField('pgtkn'); ?>
    </form>