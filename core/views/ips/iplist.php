<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-unlock"></span> <?php $theView->lang->write('HL_OPTIONS_IPBLOCKING'); ?>
    </h1>
    <form method="post" action="<?php print $theView->self; ?>?module=ips/list">
        
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-ips-list"><?php $theView->lang->write('HL_OPTIONS_IPBLOCKING'); ?></a></li>
            </ul>
            
            <div id="tabs-ips-list">
                
                <table class="fpcm-ui-table fpcm-ui-iplist">
                    <tr>
                        <th><?php $theView->lang->write('IPLIST_IPADDRESS'); ?></th>
                        <th><?php $theView->lang->write('LOGS_LIST_USER'); ?></th>
                        <th><?php $theView->lang->write('IPLIST_IPTIME'); ?></th>
                        <th class="fpcm-td-iplist-meta"></th>
                        <th class="fpcm-th-select-row"><?php fpcm\view\helper::checkbox('fpcm-select-all', '', '', '', 'fpcm-select-all', false); ?></th>
                    </tr>
                    
                    <?php \fpcm\view\helper::notFoundContainer($ipList, 5); ?>
                    
                    <tr class="fpcm-td-spacer"><td></td></tr>                    
                    <?php foreach ($ipList as $value) : ?>
                    <tr>
                        <td><?php print \fpcm\view\helper::escapeVal($value->getIpaddress()); ?></td>
                        <td><?php print isset($users[$value->getUserid()]) ? $users[$value->getUserid()]->getDisplayName() : $theView->lang->translate('GLOBAL_NOTFOUND'); ?></td>
                        <td><?php \fpcm\view\helper::dateText($value->getIptime()); ?></td>
                        <td class="fpcm-td-iplist-meta">
                            <div class="fpcm-ui-editor-metabox-right fpcm-ui-iplist-metabox-right">
                                <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $value->getNocomments(); ?>" title="<?php $theView->lang->write('IPLIST_NOCOMMENTS'); ?>">
                                    <span class="fa fa-square fa-stack-2x"></span>
                                    <span class="fa fa-comments fa-stack-1x fa-inverse"></span>
                                </span>                                
                                
                                <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $value->getNologin(); ?>" title="<?php $theView->lang->write('IPLIST_NOLOGIN'); ?>">
                                    <span class="fa fa-square fa-stack-2x"></span>
                                    <span class="fa fa-sign-in fa-stack-1x fa-inverse"></span>
                                </span>                                
                                
                                <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $value->getNoaccess(); ?>" title="<?php $theView->lang->write('IPLIST_NOACCESS'); ?>">
                                    <span class="fa fa-square fa-stack-2x"></span>
                                    <span class="fa fa-toggle-on fa-stack-1x fa-inverse"></span>
                                </span>
                            </div>
                        </td>
                        <td class="fpcm-td-select-row fpcm-filelist-checkboxes">
                            <?php fpcm\view\helper::checkbox('ipids[]', 'fpcm-list-selectbox', $value->getId(), '', '', false); ?>        
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>                
                
            </div>
            
            <div class="<?php \fpcm\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                <div class="fpcm-ui-margin-center">
                    <?php fpcm\view\helper::linkButton($theView->basePath.'ips/add', 'IPLIST_ADDIP', '', 'fpcm-loader fpcm-new-btn'); ?>
                    <?php fpcm\view\helper::deleteButton('delete'); ?>
                </div>
            </div>             

        </div>
        
        <?php \fpcm\view\helper::pageTokenField(); ?>
    </form>
</div>