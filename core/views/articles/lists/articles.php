<table class="fpcm-ui-table fpcm-ui-articles">
    <tr>
        <th></th>
        <th><?php $theView->lang->write('ARTICLE_LIST_TITLE'); ?></th>
        <th class="fpcm-ui-center fpcm-td-articlelist-categories"><?php $theView->lang->write('HL_CATEGORIES_MNG'); ?></th>
        <th class="fpcm-td-articlelist-meta"></th>
        <th class="fpcm-th-select-row"><?php fpcm\view\helper::checkbox('fpcm-select-all', '', '', '', 'fpcm-select-all', false); ?></th>
    </tr>

    <?php \fpcm\view\helper::notFoundContainer($list, 6); ?>

    <?php foreach($list AS $articleMonth => $articles) : ?>
        <tr class="fpcm-td-spacer"><td colspan="5"></td></tr>
        <tr>
            <th></th>
            <th><?php $theView->lang->writeMonth(fpcm\view\helper::dateText($articleMonth, 'n', true)); ?> <?php print fpcm\view\helper::dateText($articleMonth, 'Y', true); ?> (<?php print count($articles); ?>)</th> 
            <th class="fpcm-td-articlelist-categories"></th>
            <th class="fpcm-td-articlelist-meta"></th>
            <th class="fpcm-td-select-row"><?php fpcm\view\helper::checkbox('fpcm-select-allsub', 'fpcm-select-allsub', $articleMonth, '', 'fpcm-select-allsub'.$articleMonth, false); ?></th>
        </tr>
        <tr class="fpcm-td-spacer"><td></td></tr>
        <?php foreach($articles AS $articleId => $article) : ?>
            <tr>
                <td class="fpcm-ui-articlelist-open">                    
                    <?php (new \fpcm\view\helper\openButton('articlefe'))->setUrlbyObject($article)->setTarget('_blank'); ?>
                    <?php (new \fpcm\view\helper\editButton('articleedit'))->setUrlbyObject($article); ?>
                    <?php (new \fpcm\view\helper\clearArticleCacheButton('cac'))->setDatabyObject($article); ?>
                </td>
                <td>
                    <div class="fpcm-ui-ellipsis">
                        <strong title="<?php print substr(\fpcm\view\helper::escapeVal(strip_tags($article->getContent())), 0, 128); ?>...">
                            <?php print \fpcm\view\helper::escapeVal(strip_tags($article->getTitle())); ?>
                        </strong>
                    </div>

                    <?php if ($commentEnabledGlobal) : ?>
                    <?php (new \fpcm\view\helper\badge('badge'.$articleId)) 
                                ->setClass( (isset($commentPrivateUnapproved[$articleId]) && $commentPrivateUnapproved[$articleId] ? 'fpcm-ui-badge-red fpcm-ui-badge-comments' : 'fpcm-ui-badge-comments') )
                                ->setText( (isset($commentPrivateUnapproved[$articleId]) && $commentPrivateUnapproved[$articleId] ? 'ARTICLE_LIST_COMMENTNOTICE' : 'HL_COMMENTS_MNG') )
                                ->setValue( (isset($commentCount[$articleId]) ? $commentCount[$articleId] : 0) ); ?>
                    <?php endif; ?>

                    <div class="fpcm-ui-editor-metabox-left fpcm-articlelist-categories fpcm-hidden">
                        <strong><?php $theView->lang->write('HL_CATEGORIES_MNG'); ?>:</strong>
                        <?php print implode(', ', $article->getCategories()); ?>
                    </div>
                    <?php include $theView->getIncludePath('articles/times.php'); ?>
                </td>                
                <td class="fpcm-ui-center fpcm-td-articlelist-categories"><?php print implode(', ', $article->getCategories()); ?></td>
                <td class="fpcm-td-articlelist-meta"><?php include $theView->getIncludePath('articles/metainfo.php'); ?></td>
                <td class="fpcm-td-select-row">
                <?php if ($article->getEditPermission()) : ?>                    
                    <?php fpcm\view\helper::checkbox('actions[ids][]', 'fpcm-list-selectbox fpcm-list-selectbox-sub'.$articleMonth, $articleId, '', 'chbx'.$articleId, false); ?>
                <?php else : ?>
                    <?php fpcm\view\helper::checkbox('actions[ro][]', 'fpcm-list-selectbox fpcm-list-selectbox-sub'.$articleMonth, $articleId, '', 'chbx'.$articleId, false, true); ?>
                <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
</table>

<?php include $theView->getIncludePath('components/pager.php'); ?>