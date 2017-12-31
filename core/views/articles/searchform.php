<div class="fpcm-ui-dialog-layer fpcm-hidden" id="fpcm-dialog-articles-search">
    <table class="fpcm-ui-table fpcm-ui-articles-search">
        <tr>
            <td colspan="2"><?php \fpcm\model\view\helper::textInput('text', 'fpcm-articles-search-input', '', false, 255, $FPCM_LANG->translate('ARTICLE_SEARCH_TEXT'), 'fpcm-full-width'); ?></td>
            <td><?php \fpcm\model\view\helper::select('searchtype', $searchTypes, -1, true, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-articlesearch'); ?></td>
        </tr>
        <tr>
            <td><?php \fpcm\model\view\helper::select('userid', $searchUsers, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-articlesearch'); ?></td>
            <td><?php \fpcm\model\view\helper::select('categoryid', $searchCategories, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-articlesearch'); ?></td>
            <td></td>
        </tr>
        <tr>
            <td><?php \fpcm\model\view\helper::select('pinned', $searchPinned, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-articlesearch'); ?></td>
            <td><?php \fpcm\model\view\helper::select('postponed', $searchPostponed, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-articlesearch'); ?></td>
            <td><?php \fpcm\model\view\helper::select('comments', $searchComments, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-articlesearch'); ?></td>
        </tr>
        <tr>
            <td><?php \fpcm\model\view\helper::select('approval', $searchApproval, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-articlesearch'); ?></td>
            <td><?php \fpcm\model\view\helper::select('draft', $searchDraft, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-articlesearch'); ?></td>
            <td></td>
        </tr>
        <tr>    
            <td><?php \fpcm\model\view\helper::textInput('datefrom', 'fpcm-articles-search-input fpcm-full-width-date', '', false, 10, $FPCM_LANG->translate('ARTICLE_SEARCH_DATE_FROM'), 'fpcm-full-width'); ?></td>
            <td><?php \fpcm\model\view\helper::textInput('dateto', 'fpcm-articles-search-input fpcm-full-width-date', '', false, 10, $FPCM_LANG->translate('ARTICLE_SEARCH_DATE_TO'), 'fpcm-full-width'); ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><?php \fpcm\model\view\helper::select('combination', $searchCombination, null, false, false, false, 'fpcm-articles-search-input fpcm-ui-input-select-articlesearch'); ?></td>
        </tr>
    </table>
</div>