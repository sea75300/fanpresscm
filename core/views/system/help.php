<div class="fpcm-content-wrapper">
    <h1><span class="fa fa-question-circle"></span> <?php $FPCM_LANG->write('HL_HELP'); ?></h1>
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-help-general"><?php $FPCM_LANG->write('HL_HELP'); ?></a></li>
        </ul>

        <div id="tabs-help-general">
            <div class="fpcm-tabs-accordion">
                
                <p><?php $FPCM_LANG->write('HELP_SELECT'); ?></p>
                
                <?php foreach ($chapters as $headline => $text) : ?>
                    <h2><?php $FPCM_LANG->write($headline); ?></h2>
                    <div><?php print $text; ?></div>
                <?php endforeach; ?>       
            </div>            
        </div>        
    </div>
</div>