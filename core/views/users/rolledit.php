<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-roll"><?php $theView->write($tabsHeadline); ?></a></li>
        </ul>            

        <div id="tabs-roll">
            <div class="row fpcm-ui-padding-md-tb no-gutters">
                <div class="col-12">
                    <div class="row">
                        <?php $theView->textInput('rollname')
                            ->setValue($userRoll->getRollName())
                            ->setText('USERS_ROLLS_NAME')
                            ->setDisplaySizesDefault(); ?>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>