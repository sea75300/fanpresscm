<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-ip"><?php $theView->write('IPLIST_ADDIP'); ?></a></li>
        </ul>            

        <div id="tabs-ip">                
            <table class="fpcm-ui-table">
                <tr>
                    <td colspan="2">
                        <p><?php $theView->write('IPLIST_DESCRIPTION'); ?></p>
                    </td>
                </tr>
                <tr>
                    <td><?php $theView->write('IPLIST_IPADDRESS'); ?>:</td>
                    <td>
                        <?php \fpcm\view\helper::textInput('ipaddress'); ?>
                    </td>
                </tr>
                <tr>
                    <td><?php $theView->write('IPLIST_BLOCKTYPE'); ?>:</td>
                    <td class="fpcm-ui-controlgroup">
                        <?php fpcm\view\helper::checkbox('nocomments', '', '1', 'IPLIST_NOCOMMENTS', 'nocomments', false); ?> 
                        <?php fpcm\view\helper::checkbox('nologin', '', '1', 'IPLIST_NOLOGIN', 'nologin', false); ?> 
                        <?php fpcm\view\helper::checkbox('noaccess', '', '1', 'IPLIST_NOACCESS', 'noaccess', false); ?> 
                    </td>
                </tr>
            </table>            

            <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                <div class="fpcm-ui-margin-center">
                    <?php $theView->saveButton('ipSave'); ?>
                </div>
            </div> 
        </div>
    </div>

    <?php $theView->pageTokenField('pgtkn'); ?>
</div>