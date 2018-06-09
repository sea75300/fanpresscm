<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if (!isset($headline) || !isset($dataViewId)) : ?>
<p><?php $theView->write(__FILE__.' required to assign variables "$headline" and "$dataViewId"!'); ?></p>
<?php else: ?>
<div class="row no-gutters fpcm-ui-full-height">
    <div class="col-12">
        <div class="fpcm-content-wrapper fpcm-ui-full-height">
            <div class="fpcm-ui-tabs-general">
                <ul>
                    <li><a href="#tabs-<?php print $dataViewId; ?>-list"><?php $theView->write($headline); ?></a></li>                
                </ul>

                <div id="tabs-<?php print $dataViewId; ?>-list">

                    <div id="fpcm-dataview-<?php print $dataViewId; ?>"></div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>