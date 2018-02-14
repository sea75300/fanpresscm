<table class="fpcm-ui-table fpcm-ui-articles">
    <tr>
        <th></th>
        <th><?php $theView->write('ARTICLE_LIST_TITLE'); ?></th>
        <th class="fpcm-ui-center fpcm-td-articlelist-categories"><?php $theView->write('HL_CATEGORIES_MNG'); ?></th>
        <th class="fpcm-td-articlelist-meta"></th>
        <th class="fpcm-th-select-row"><?php $theView->checkbox('fpcm-select-all')->setClass('fpcm-select-all'); ?></th>
    </tr>

    <?php \fpcm\view\helper::notFoundContainer($list, 6); ?>

    <?php foreach($list AS $articleMonth => $articles) : ?>
        <tr class="fpcm-td-spacer"><td colspan="5"></td></tr>
        <tr>
            <th></th>
            <th><?php $theView->writeMonth($theView->dateText($articleMonth, 'n')); ?> <?php print $theView->dateText($articleMonth, 'Y'); ?> (<?php print count($articles); ?>)</th> 
            <th class="fpcm-td-articlelist-categories"></th>
            <th class="fpcm-td-articlelist-meta"></th>
            <th class="fpcm-td-select-row"><?php $theView->checkbox('fpcm-select-allsub', 'fpcm-select-allsub'.$articleMonth)->setClass('fpcm-select-allsub')->setValue($articleMonth); ?></th>
        </tr>
        <tr class="fpcm-td-spacer"><td></td></tr>
        <?php foreach($articles AS $articleId => $article) : ?>
            <tr>
                <td class="fpcm-ui-articlelist-open">                    
                    <?php $theView->openButton('articlefe')->setUrlbyObject($article)->setTarget('_blank'); ?>
                    <?php $theView->editButton('articleedit')->setUrlbyObject($article); ?>
                    <?php $theView->clearArticleCacheButton('cac')->setDatabyObject($article); ?>
                </td>
                <td>
                    <div class="fpcm-ui-ellipsis">
                        <strong title="<?php print substr($theView->escape(strip_tags($article->getContent())), 0, 128); ?>...">
                            <?php print $theView->escape(strip_tags($article->getTitle())); ?>
                        </strong>
                    </div>

                    <?php if ($commentEnabledGlobal) : ?>
                    <?php $theView->badge('badge'.$articleId)
                            ->setClass( (isset($commentPrivateUnapproved[$articleId]) && $commentPrivateUnapproved[$articleId] ? 'fpcm-ui-badge-red fpcm-ui-badge-comments' : 'fpcm-ui-badge-comments') )
                            ->setText( (isset($commentPrivateUnapproved[$articleId]) && $commentPrivateUnapproved[$articleId] ? 'ARTICLE_LIST_COMMENTNOTICE' : 'HL_COMMENTS_MNG') )
                            ->setValue( (isset($commentCount[$articleId]) ? $commentCount[$articleId] : 0) ); ?>
                    <?php endif; ?>

                    <div class="fpcm-ui-editor-metabox-left fpcm-articlelist-categories fpcm-ui-hidden">
                        <strong><?php $theView->write('HL_CATEGORIES_MNG'); ?>:</strong>
                        <?php print implode(', ', $article->getCategories()); ?>
                    </div>
                    <?php include $theView->getIncludePath('articles/times.php'); ?>
                </td>                
                <td class="fpcm-ui-center fpcm-td-articlelist-categories"><?php print implode(', ', $article->getCategories()); ?></td>
                <td class="fpcm-td-articlelist-meta"><?php include $theView->getIncludePath('articles/metainfo.php'); ?></td>
                <td class="fpcm-td-select-row">
                    <?php $nameList = $article->getEditPermission() ? 'ids' : 'ro'; ?>                    
                    <?php $theView->checkbox('actions['.$nameList.'][]', 'chbx'.$articleId)->setClass('fpcm-list-selectbox fpcm-list-selectbox-sub'.$articleMonth)->setValue($articleId)->setReadonly($article->getEditPermission()); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
</table>

<?php include $theView->getIncludePath('components/pager.php'); ?>