<div id="fpcm-messages" class="fpcm-messages">    
<?php if (isset($FPCM_MESSAGES) && is_array($FPCM_MESSAGES) && count($FPCM_MESSAGES)) : ?>
        <?php foreach ($FPCM_MESSAGES as $message) : ?>
        <div class="fpcm-message-box fpcm-message-<?php print $message['type']; ?>" id="msgbox-<?php print $message['id']; ?>">
            <div class="fpcm-msg-icon">
                <span class="fa-stack fa-lg">
                    <span class="fa fa-square fa-stack-2x fa-inverse"></span>
                    <span class="fa fa-<?php print $message['icon']; ?> fa-stack-1x"></span>
                </span>
            </div>

            <div class="fpcm-msg-text"><?php print $message['txt']; ?></div>
            
            <div class="fpcm-msg-close" id="msgclose-<?php print $message['id']; ?>">
                <span class="fa-stack fa-lg">
                  <span class="fa fa-square fa-stack-2x fa-inverse"></span>
                  <span class="fa fa-times fa-stack-1x"></span>
                </span>
            </div>
            
            <div class="fpcm-clear"></div>
        </div>
        <?php endforeach; ?>
<?php endif; ?>
</div>