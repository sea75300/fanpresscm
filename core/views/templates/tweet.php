<table class="fpcm-ui-table">
    <?php include $theView->getIncludePath('templates/replacementhead.php'); ?>
    <tr>
        <td class="fpcm-ui-template-replacements ui-widget-content ui-corner-all ui-state-normal">
            <dl>
            <?php foreach ($replacementsTweet as $tag => $descr) : ?>
                <dt><?php print $tag; ?></dt>        
                <dd><?php print $descr; ?></dd>
            <?php endforeach; ?>
            </dl>
        </td>
    </tr>
    <tr class="fpcm-td-spacer"><td></td></tr>
    <tr>
        <th class="fpcm-th-full"><?php $theView->lang->write('TEMPLATE_EDITOR'); ?></th>
    </tr>
    <tr>
        <td>
            <?php fpcm\view\helper::textArea('template[tweet]', 'fpcm-full-width', $contentTweet); ?>
        </td>
    </tr>                    
</table>