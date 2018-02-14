<div class="fpcm-content-wrapper">
    <form method="post" action="<?php print $theView->self; ?>?module=categories/list">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-users-active"><?php $theView->write('HL_CATEGORIES_MNG'); ?></a></li>
            </ul>            
            
            <div id="tabs-users-active">
                <table class="fpcm-ui-table fpcm-ui-categories">
                    <tr>
                        <th class="fpcm-ui-editbutton-col"></th>
                        <th><?php $theView->write('CATEGORIES_NAME'); ?></th>
                        <th><?php $theView->write('CATEGORIES_ICON_PATH'); ?></th>
                        <th><?php $theView->write('CATEGORIES_ROLLS'); ?></th>
                        <th class="fpcm-td-select-row"></th>         
                    </tr>
                    <tr class="fpcm-td-spacer"><td></td></tr>
                    
                    <?php foreach($categorieList AS $cat) : ?>
                    <tr>
                        <td class="fpcm-ui-editbutton-col"><?php \fpcm\view\helper::editButton($cat->getEditLink()); ?></td>
                        <td><strong><?php print $theView->escape($cat->getName()); ?></strong></td>
                        <td><?php if ($cat->getIconPath()) : ?> <img src="<?php print $cat->getIconPath(); ?>" alt="<?php print $cat->getName(); ?>"><?php endif; ?></td>
                        <td><?php print $cat->getGroups(); ?></td>
                        <td class="fpcm-td-select-row"><input type="radio" name="ids" value="<?php print $cat->getId(); ?>"></td>
                    </tr>      
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <?php $theView->pageTokenField('pgtkn'); ?>
    </form>
</div>