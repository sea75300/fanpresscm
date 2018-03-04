<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-users-active"><?php $theView->write('HL_OPTIONS_WORDBAN'); ?></a></li>
        </ul>            

        <div id="tabs-users-active">
            <table class="fpcm-ui-table fpcm-ui-categories">
                <tr>
                    <th class="fpcm-ui-editbutton-col"></th>
                    <th><?php $theView->write('WORDBAN_NAME'); ?></th>
                    <th><?php $theView->write('WORDBAN_ICON_PATH'); ?></th>
                    <th class="fpcm-td-articlelist-meta"></th>
                    <th class="fpcm-td-select-row"></th>         
                </tr>
                <?php \fpcm\view\helper::notFoundContainer($itemList, 4); ?>

                <tr class="fpcm-td-spacer"><td></td></tr>

                <?php foreach($itemList AS $item) : ?>
                <tr>
                    <td class="fpcm-ui-editbutton-col"><?php \fpcm\view\helper::editButton($item->getEditLink()); ?></td>
                    <td><strong><?php print $theView->escape($item->getSearchtext()); ?></strong></td>
                    <td><?php print $theView->escape($item->getReplacementtext()); ?></td>
                    <td class="fpcm-td-articlelist-meta">
                        <div class="fpcm-ui-editor-metabox-right">
                            <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $item->getReplaceTxt(); ?>" title="<?php $theView->write('WORDBAN_REPLACETEXT'); ?>">
                                <span class="fa fa-square fa-stack-2x"></span>
                                <span class="fa fa-search fa-stack-1x fa-inverse"></span>
                            </span>

                            <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $item->getLockArticle(); ?>" title="<?php $theView->write('WORDBAN_APPROVE_ARTICLE'); ?>">
                                <span class="fa fa-square fa-stack-2x"></span>
                                <span class="fa fa-thumbs-o-up fa-stack-1x fa-inverse"></span>
                            </span>

                            <span class="fa-stack fa-fw fpcm-ui-editor-metainfo fpcm-ui-status-<?php print $item->getCommentApproval(); ?>" title="<?php $theView->write('WORDBAN_APPROVA_COMMENT'); ?>">
                                <span class="fa fa-square fa-stack-2x"></span>
                                <span class="fa fa-check-circle-o fa-stack-1x fa-inverse"></span>
                            </span>
                        </div>
                    </td>
                    <td class="fpcm-td-select-row"><?php fpcm\view\helper::checkbox('ids[]', 'fpcm-ui-list-checkbox', $item->getId(), '', '', false); ?></td>
                </tr>      
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <?php $theView->pageTokenField('pgtkn'); ?>
</div>