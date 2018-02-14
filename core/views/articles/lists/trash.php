<table class="fpcm-ui-table fpcm-ui-articles">
    <tr>
        <th></th>
        <th><?php $theView->write('ARTICLE_LIST_TITLE'); ?></th>
        <th class="fpcm-th-select-row"><?php fpcm\view\helper::checkbox('fpcm-select-all', '', '', '', 'fpcm-select-all-trash', false); ?></th>
    </tr>

    <?php \fpcm\view\helper::notFoundContainer($list, 3); ?>

    <?php foreach($list AS $articleMonth => $articles) : ?>
        <tr class="fpcm-td-spacer"><td></td></tr>
        <tr>
            <th></th>
            <th><?php $theView->writeMonth($theView->dateText($articleMonth, 'n')); ?> <?php print $theView->dateText($articleMonth, 'Y'); ?></th> 
            <th class="fpcm-td-select-row"><?php fpcm\view\helper::checkbox('fpcm-select-allsub', 'fpcm-select-allsub', '-trash'.$articleMonth, '', 'fpcm-select-allsub', false); ?></th>
        </tr>
        <tr class="fpcm-td-spacer"><td></td></tr>
        <?php foreach($articles AS $articleId => $article) : ?>
            <tr>
                <td><?php $theView->openButton('articlefe')->setUrlbyObject($article)->setTarget('_blank'); ?></td>
                <td class="fpcm-ui-ellipsis"><strong><?php print $theView->escape(strip_tags($article->getTitle())); ?></strong></td>
                <td class="fpcm-td-select-row"><?php fpcm\view\helper::checkbox('actions[ids][]', 'fpcm-list-selectbox-trash fpcm-list-selectbox-sub-trash'.$articleMonth, $articleId, '', 'chbx'.$articleId, false) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>                    
</table>