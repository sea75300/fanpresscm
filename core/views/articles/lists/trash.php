<table class="fpcm-ui-table fpcm-ui-articles">
    <tr>
        <th></th>
        <th><?php $theView->write('ARTICLE_LIST_TITLE'); ?></th>
        <th class="fpcm-th-select-row"><?php $theView->checkbox('fpcm-select-all')->setClass('fpcm-select-all'); ?></th>
    </tr>

    <?php \fpcm\view\helper::notFoundContainer($list, 3); ?>

    <?php foreach($list AS $articleMonth => $articles) : ?>
        <tr class="fpcm-td-spacer"><td></td></tr>
        <tr>
            <th></th>
            <th><?php $theView->writeMonth($theView->dateText($articleMonth, 'n')); ?> <?php print $theView->dateText($articleMonth, 'Y'); ?></th> 
            <th class="fpcm-td-select-row"><?php $theView->checkbox('fpcm-ui-list-checkbox-sub', 'fpcm-ui-list-checkbox-sub'.$articleMonth)->setClass('fpcm-ui-list-checkbox-sub')->setValue($articleMonth); ?></th>
        </tr>
        <tr class="fpcm-td-spacer"><td></td></tr>
        <?php foreach($articles AS $articleId => $article) : ?>
            <tr>
                <td><?php $theView->openButton('articlefe')->setUrlbyObject($article)->setTarget('_blank'); ?></td>
                <td class="fpcm-ui-ellipsis"><strong><?php print $theView->escape(strip_tags($article->getTitle())); ?></strong></td>
                <td class="fpcm-td-select-row"><?php $theView->checkbox('actions[ids][]', 'chbx'.$articleId)->setClass('fpcm-ui-list-checkbox fpcm-ui-list-checkbox-subitem'.$articleMonth)->setValue($articleId); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>                    
</table>