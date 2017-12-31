<table class="fpcm-ui-table fpcm-ui-articles">
    <tr>
        <th></th>
        <th><?php $FPCM_LANG->write('ARTICLE_LIST_TITLE'); ?></th>
        <th class="fpcm-th-select-row"><?php fpcm\model\view\helper::checkbox('fpcm-select-all', '', '', '', 'fpcm-select-all-trash', false); ?></th>
    </tr>

    <?php \fpcm\model\view\helper::notFoundContainer($list, 3); ?>

    <?php foreach($list AS $articleMonth => $articles) : ?>
        <tr class="fpcm-td-spacer"><td></td></tr>
        <tr>
            <th></th>
            <th><?php $FPCM_LANG->writeMonth(fpcm\model\view\helper::dateText($articleMonth, 'n', true)); ?> <?php print fpcm\model\view\helper::dateText($articleMonth, 'Y', true); ?></th> 
            <th class="fpcm-td-select-row"><?php fpcm\model\view\helper::checkbox('fpcm-select-allsub', 'fpcm-select-allsub', '-trash'.$articleMonth, '', 'fpcm-select-allsub', false); ?></th>
        </tr>
        <tr class="fpcm-td-spacer"><td></td></tr>
        <?php foreach($articles AS $articleId => $article) : ?>
            <tr>
                <td><?php \fpcm\model\view\helper::linkButton($article->getArticleLink(), 'GLOBAL_FRONTEND_OPEN', '', 'fpcm-ui-button-blank fpcm-openlink-btn'); ?></td>
                <td class="fpcm-ui-ellipsis"><strong><?php print \fpcm\model\view\helper::escapeVal(strip_tags($article->getTitle())); ?></strong></td>
                <td class="fpcm-td-select-row"><?php fpcm\model\view\helper::checkbox('actions[ids][]', 'fpcm-list-selectbox-trash fpcm-list-selectbox-sub-trash'.$articleMonth, $articleId, '', 'chbx'.$articleId, false) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>                    
</table>