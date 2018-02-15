<div class="fpcm-content-wrapper">
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
        </div>
    </div>
</div>