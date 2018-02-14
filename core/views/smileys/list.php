<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-smiley-list"><?php $theView->write('HL_OPTIONS_SMILEYS'); ?></a></li>                
        </ul>

        <div id="tabs-smiley-list">
            <table class="fpcm-ui-table fpcm-ui-smileys">
                <tr>
                    <th class="fpcm-ui-smiley-listimg"></th>
                    <th><?php $theView->write('FILE_LIST_FILENAME'); ?></th>
                    <th><?php $theView->write('FILE_LIST_SMILEYCODE'); ?></th>
                    <th class="fpcm-th-select-row"><?php fpcm\view\helper::checkbox('fpcm-select-all', '', '', '', 'fpcm-select-all', false); ?></th>
                </tr>
                <?php \fpcm\view\helper::notFoundContainer($list, 4); ?>
                <tr class="fpcm-td-spacer"><td></td></tr>
                <?php foreach ($list as $smiley) : ?>
                <tr>
                    <td class="fpcm-ui-smiley-listimg"><img src="<?php print $smiley->getSmileyUrl(); ?>" alt="<?php print $smiley->getFilename(); ?>" <?php print $smiley->getWhstring(); ?>></td>
                    <td><?php print $theView->escape($smiley->getFilename()); ?></td>
                    <td><?php print $theView->escape($smiley->getSmileyCode()); ?></td>
                    <td class="fpcm-td-select-row"><?php fpcm\view\helper::checkbox('smileyids[]', 'fpcm-list-selectbox', base64_encode(serialize(array($smiley->getFilename(), $smiley->getSmileyCode()))), '', '', false) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <?php $theView->pageTokenField('pgtkn'); ?>
</div>