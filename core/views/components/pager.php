<?php if ($showPager) : ?>
<table class="fpcm-ui-table fpcm-ui-articlelist-acp fpcm-ui-margin-center">
    <tr>
        <td class="fpcm-ui-center">
            <?php if ($backBtn) : ?>
                <?php \fpcm\model\view\helper::linkButton($backBtn > 1 ? $FPCM_SELF.'?module='.$listAction.'&page='.$backBtn : $FPCM_SELF.'?module='.$listAction, 'GLOBAL_BACK', 'fpcm-pager-back', 'fpcm-ui-pager-buttons fpcm-back-button'); ?>
            <?php else : ?>            
                <?php \fpcm\model\view\helper::dummyButton('GLOBAL_BACK', 'fpcm-ui-pager-buttons fpcm-back-button fpcm-ui-readonly'); ?>            
            <?php endif; ?>
        </td>
        <td class="fpcm-ui-center">            
            <?php \fpcm\model\view\helper::select('pageSelect', $pageSelectOptions, $pageCurrent, false, false); ?>
        </td>
        <td class="fpcm-ui-center">
            <?php if ($nextBtn) : ?>
                <?php \fpcm\model\view\helper::linkButton($FPCM_SELF.'?module='.$listAction.'&page='.$nextBtn, 'GLOBAL_NEXT', 'fpcm-pager-next', 'fpcm-ui-pager-buttons fpcm-forward-button'); ?>
            <?php else : ?>            
                <?php \fpcm\model\view\helper::dummyButton('GLOBAL_NEXT', 'fpcm-ui-pager-buttons fpcm-forward-button fpcm-ui-readonly'); ?>            
            <?php endif; ?>
        </td>
    </tr>    
</table>
<?php endif; ?>