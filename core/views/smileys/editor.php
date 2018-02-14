<table class="fpcm-ui-table">
    <tr>
        <td><?php $theView->write('FILE_LIST_SMILEYCODE'); ?>:</td>
        <td>
            <?php \fpcm\view\helper::textInput('smiley[code]', '', $smiley->getSmileyCode()); ?>
        </td>
    </tr>
    <tr>
        <td><?php $theView->write('FILE_LIST_FILENAME'); ?>:</td>
        <td>
            <?php \fpcm\view\helper::textInput('smiley[filename]', '', $smiley->getFilename()); ?>
        </td>
    </tr>                    
</table>