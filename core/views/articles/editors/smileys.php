<?php $count = 1; ?>
<div role="presentation">
    <table class="fpcm-ui-table fpcm-ui-editor-smileys">
        <tr>
            <?php foreach ($smileys as $key => $smiley) : ?>
                <td>                        
                    <img smileycode="<?php print $smiley->getSmileyCode(); ?>" class="fpcm-editor-htmlsmiley" src="<?php print $smiley->getSmileyUrl(); ?>" alt="<?php print $smiley->getFilename(); ?> (<?php print $smiley->getSmileyCode(); ?>)" title="<?php print $smiley->getFilename(); ?> (<?php print $smiley->getSmileyCode(); ?>)" <?php print $smiley->getWhstring(); ?>>
                </td>
                <?php if ($count % 10 == 0) : ?></tr><tr><?php $count = 0; ?><?php endif; ?>            
                <?php $count++; ?>
            <?php endforeach;  ?>          
        </tr>
    </table>
</div>    