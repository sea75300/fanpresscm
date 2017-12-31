<div class="fpcm-content-wrapper">
    <h1>
        <span class="fa fa-ban"></span> <?php $FPCM_LANG->write('HL_OPTIONS_WORDBAN'); ?>
    </h1>
    <form method="post" action="<?php print $FPCM_SELF; ?>?module=wordban/list">
        <div class="fpcm-tabs-general">
            <ul>
                <li><a href="#tabs-users-active"><?php $FPCM_LANG->write('HL_OPTIONS_WORDBAN'); ?></a></li>
            </ul>            
            
            <div id="tabs-users-active">
                <table class="fpcm-ui-table fpcm-ui-categories">
                    <tr>
                        <th class="fpcm-ui-editbutton-col"></th>
                        <th><?php $FPCM_LANG->write('WORDBAN_NAME'); ?></th>
                        <th><?php $FPCM_LANG->write('WORDBAN_ICON_PATH'); ?></th>
                        <th class="fpcm-td-articlelist-meta"></th>
                        <th class="fpcm-td-select-row"></th>         
                    </tr>
                    <?php \fpcm\model\view\helper::notFoundContainer($itemList, 4); ?>

                    <tr class="fpcm-td-spacer"><td></td></tr>

                    <?php foreach($itemList AS $item) : ?>
                    <tr>
                        <td class="fpcm-ui-editbutton-col"><?php \fpcm\model\view\helper::editButton($item->getEditLink()); ?></td>
                        <td><strong><?php print \fpcm\model\view\helper::escapeVal($item->getSearchtext()); ?></strong></td>
                        <td><?php print \fpcm\model\view\helper::escapeVal($item->getReplacementtext()); ?></td>
                        <td class="fpcm-td-articlelist-meta">
                            <div class="fpcm-ui-editor-metabox-right">
                                <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $item->getReplaceTxt(); ?>" title="<?php $FPCM_LANG->write('WORDBAN_REPLACETEXT'); ?>">
                                    <span class="fa fa-square fa-stack-2x"></span>
                                    <span class="fa fa-search fa-stack-1x fa-inverse"></span>
                                </span>

                                <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $item->getLockArticle(); ?>" title="<?php $FPCM_LANG->write('WORDBAN_APPROVE_ARTICLE'); ?>">
                                    <span class="fa fa-square fa-stack-2x"></span>
                                    <span class="fa fa-thumbs-o-up fa-stack-1x fa-inverse"></span>
                                </span>

                                <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $item->getCommentApproval(); ?>" title="<?php $FPCM_LANG->write('WORDBAN_APPROVA_COMMENT'); ?>">
                                    <span class="fa fa-square fa-stack-2x"></span>
                                    <span class="fa fa-check-circle-o fa-stack-1x fa-inverse"></span>
                                </span>
                            </div>
                        </td>
                        <td class="fpcm-td-select-row"><?php fpcm\model\view\helper::checkbox('ids[]', 'fpcm-list-selectbox', $item->getId(), '', '', false); ?></td>
                    </tr>      
                    <?php endforeach; ?>
                </table>
                
                <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-ui-list-buttons">
                    <div class="fpcm-ui-margin-center">
                        <?php fpcm\model\view\helper::linkButton($FPCM_BASEMODULELINK.'wordban/add', $FPCM_LANG->translate('WORDBAN_ADD'), '', 'fpcm-loader fpcm-new-btn'); ?>
                        <?php fpcm\model\view\helper::deleteButton('delete'); ?>
                    </div>
                </div>
            </div>
        </div>

    <?php \fpcm\model\view\helper::pageTokenField(); ?>
    </form>
</div>