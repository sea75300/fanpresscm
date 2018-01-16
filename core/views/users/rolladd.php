<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-user-plus"></span> <?php $theView->lang->write('HL_OPTIONS_USERS'); ?></h1>
    <form method="post" action="<?php print $theView->self; ?>?module=users/addroll">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-roll"><?php $theView->lang->write('USERS_ROLL_ADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-roll">
                <table class="fpcm-ui-table">
                    <tr>
                        <td><?php $theView->lang->write('USERS_ROLLS_NAME'); ?>:</td>
                        <td>
                            <?php \fpcm\view\helper::textInput('rollname'); ?>
                        </td>
                    </tr>      
                </table>            

                <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php \fpcm\view\helper::saveButton('saveRoll'); ?>
                    </div>
                </div>                
            </div>
        </div>
    </form>
</div>