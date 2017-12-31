<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-file-o"></span> <?php $FPCM_LANG->write('HL_CATEGORIES_MNG'); ?>
    </h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=categories/list">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-users-active"><?php $FPCM_LANG->write('HL_CATEGORIES_MNG'); ?></a></li>
            </ul>            
            
            <div id="tabs-users-active">
                <table class="fpcm-ui-table fpcm-ui-categories">
                    <tr>
                        <th class="fpcm-ui-editbutton-col"></th>
                        <th><?php $FPCM_LANG->write('CATEGORIES_NAME'); ?></th>
                        <th><?php $FPCM_LANG->write('CATEGORIES_ICON_PATH'); ?></th>
                        <th><?php $FPCM_LANG->write('CATEGORIES_ROLLS'); ?></th>
                        <th class="fpcm-td-select-row"></th>         
                    </tr>
                    <tr class="fpcm-td-spacer"><td></td></tr>
                    
                    <?php foreach($categorieList AS $cat) : ?>
                    <tr>
                        <td class="fpcm-ui-editbutton-col"><?php \fpcm\model\view\helper::editButton($cat->getEditLink()); ?></td>
                        <td><strong><?php print \fpcm\model\view\helper::escapeVal($cat->getName()); ?></strong></td>
                        <td><?php if ($cat->getIconPath()) : ?> <img src="<?php print $cat->getIconPath(); ?>" alt="<?php print $cat->getName(); ?>"><?php endif; ?></td>
                        <td><?php print $cat->getGroups(); ?></td>
                        <td class="fpcm-td-select-row"><input type="radio" name="ids" value="<?php print $cat->getId(); ?>"></td>
                    </tr>      
                    <?php endforeach; ?>
                </table>
                
                <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php fpcm\model\view\helper::linkButton($FPCM_BASEMODULELINK.'categories/add', $FPCM_LANG->translate('CATEGORIES_ADD'), '', 'fpcm-loader fpcm-new-btn'); ?>
                        <?php fpcm\model\view\helper::deleteButton('delete'); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php \fpcm\model\view\helper::pageTokenField(); ?>
    </form>
</div>