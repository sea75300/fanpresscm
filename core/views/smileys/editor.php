<table class="fpcm-ui-table">
    <tr>
        <td><?php $FPCM_LANG->write('FILE_LIST_SMILEYCODE'); ?>:</td>
        <td>
            <?php \fpcm\model\view\helper::textInput('smiley[code]', '', $smiley->getSmileyCode()); ?>
        </td>
    </tr>
    <tr>
        <td><?php $FPCM_LANG->write('FILE_LIST_FILENAME'); ?>:</td>
        <td>
            <?php \fpcm\model\view\helper::textInput('smiley[filename]', '', $smiley->getFilename()); ?>
        </td>
    </tr>                    
</table>            

<div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
    <div class="fpcm-ui-margin-center">
        <?php \fpcm\model\view\helper::saveButton('saveSmiley'); ?>
    </div>
</div>