<table class="fpcm-ui-table">
    <tr>
        <td><?php $theView->write('CATEGORIES_NAME'); ?>:</td>
        <td><?php \fpcm\view\helper::textInput('category[name]','',$category->getName()); ?></td>
    </tr>
    <tr>
        <td><?php $theView->write('CATEGORIES_ICON_PATH'); ?>:</td>
        <td><?php \fpcm\view\helper::textInput('category[iconpath]','',$category->getIconPath()); ?></td>
    </tr>
    <tr>
        <td><?php $theView->write('CATEGORIES_ROLLS'); ?></td>
        <td>
            <div class="fpcm-ui-controlgroup">
            <?php foreach ($userRolls as $rollname => $rollid) : ?>
                <?php $theView->checkbox('category[groups][]', 'cat'.$rollid)->setText($rollname)->setValue($rollid)->setSelected(isset($selectedGroups) && in_array($rollid, $selectedGroups) ? true : false); ?>
            <?php endforeach; ?>
            </div>
        </td>
    </tr>               
</table>