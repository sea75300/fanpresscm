<div class="fpcm-content-wrapper">
    
    <form method="post" action="<?php print $theView->self; ?>?module=users/addroll">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-roll"><?php $theView->write('USERS_ROLL_ADD'); ?></a></li>
            </ul>            
            
            <div id="tabs-roll">
                <table class="fpcm-ui-table">
                    <tr>
                        <td><?php $theView->write('USERS_ROLLS_NAME'); ?>:</td>
                        <td>
                            <?php \fpcm\view\helper::textInput('rollname'); ?>
                        </td>
                    </tr>      
                </table>            

                <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php $theView->saveButton('saveRoll'); ?>
                    </div>
                </div>                
            </div>
        </div>
    </form>
</div>