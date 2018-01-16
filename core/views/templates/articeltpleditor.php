    <form method="post" action="<?php print $file->getEditUrl(); ?>" enctype="multipart/form-data">
        <table class="fpcm-ui-table fpcm-ui-articletemplates">
            <tr>
                <td>
                    <?php fpcm\model\view\helper::textArea('templatecode', 'fpcm-full-width', $file->getContent()); ?>
                </td>
            </tr>  
        </table>

        <div class="fpcm-hidden"><?php \fpcm\view\helper::saveButton('saveTemplate'); ?></div>

        <?php \fpcm\view\helper::pageTokenField(); ?>
    </form>