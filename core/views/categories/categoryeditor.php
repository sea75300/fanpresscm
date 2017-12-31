<table class="fpcm-ui-table">
    <tr>
        <td><?php $FPCM_LANG->write('CATEGORIES_NAME'); ?>:</td>
        <td><?php \fpcm\model\view\helper::textInput('category[name]','',$category->getName()); ?></td>
    </tr>
    <tr>
        <td><?php $FPCM_LANG->write('CATEGORIES_ICON_PATH'); ?>:</td>
        <td><?php \fpcm\model\view\helper::textInput('category[iconpath]','',$category->getIconPath()); ?></td>
    </tr>
    <tr>
        <td><?php $FPCM_LANG->write('CATEGORIES_ROLLS'); ?></td>
        <td class="fpcm-ui-buttonset">
        <?php foreach ($userRolls as $rollname => $rollid) : ?>
            <?php
                if (isset($selectedGroups)) {
                    $selected = in_array($rollid, $selectedGroups) ? true : false;
                } else {
                    $selected = false;
                }
            ?>
            <?php fpcm\model\view\helper::checkbox('category[groups][]', '', $rollid, $rollname, 'cat'.$rollid, $selected); ?>
        <?php endforeach; ?>                    
        </td>
    </tr>               
</table> 

<div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
    <div class="fpcm-ui-margin-center">
        <?php \fpcm\model\view\helper::saveButton('categorySave'); ?>
    </div>
</div>