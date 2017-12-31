<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-unlock"></span> <?php $FPCM_LANG->write('HL_OPTIONS_IPBLOCKING'); ?></h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=ips/add">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-ip"><?php $FPCM_LANG->write('IPLIST_ADDIP'); ?></a></li>
            </ul>            
            
            <div id="tabs-ip">                
                <table class="fpcm-ui-table">
                    <tr>
                        <td colspan="2">
                            <p><?php $FPCM_LANG->write('IPLIST_DESCRIPTION'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td><?php $FPCM_LANG->write('IPLIST_IPADDRESS'); ?>:</td>
                        <td>
                            <?php \fpcm\model\view\helper::textInput('ipaddress'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php $FPCM_LANG->write('IPLIST_BLOCKTYPE'); ?>:</td>
                        <td class="fpcm-ui-buttonset">
                            <?php fpcm\model\view\helper::checkbox('nocomments', '', '1', 'IPLIST_NOCOMMENTS', 'nocomments', false); ?> 
                            <?php fpcm\model\view\helper::checkbox('nologin', '', '1', 'IPLIST_NOLOGIN', 'nologin', false); ?> 
                            <?php fpcm\model\view\helper::checkbox('noaccess', '', '1', 'IPLIST_NOACCESS', 'noaccess', false); ?> 
                        </td>
                    </tr>
                </table>            

                <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php \fpcm\model\view\helper::saveButton('ipSave'); ?>
                    </div>
                </div> 
            </div>
        </div>
        
        <?php \fpcm\model\view\helper::pageTokenField(); ?>
    </form>
</div>