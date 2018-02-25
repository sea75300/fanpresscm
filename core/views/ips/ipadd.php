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
                    <td>
                        <div class="fpcm-ui-controlgroup">
                            <?php $theView->checkbox('nocomments')->setText('IPLIST_NOCOMMENTS'); ?>
                            <?php $theView->checkbox('nologin')->setText('IPLIST_NOLOGIN'); ?>
                            <?php $theView->checkbox('noaccess')->setText('IPLIST_NOACCESS'); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <?php $theView->pageTokenField('pgtkn'); ?>
</div>