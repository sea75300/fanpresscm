<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general" id="tabs-module-<?php print $prefix; ?>-config">
        <ul>
            <li><a href="#tabs-config-<?php print $prefix; ?>"><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></a></li>
        </ul>            

        <div id="tabs-config-<?php print $prefix; ?>">
            <?php if (!empty($descriptions['top'])) : ?>
            <div class="row g-0 mx-0 mt-2 mb-3">
                <div class="col-12">
                    <fieldset class="m-0">
                        <legend><?php $theView->write($descriptions['top']['headline']); ?></legend>
                        <?php $theView->write($descriptions['top']['text']); ?>
                    </fieldset>
                </div>
            </div>
            <?php endif; ?>            
            
            
        <?php foreach ($fields as $option => $field) : ?>
            <div class="row my-2 mx-0">
                <?php print $field; ?>
            </div>
        <?php endforeach; ?>
            
            <?php if (!empty($descriptions['buttom'])) : ?>
            <div class="row g-0 mx-0 mt-3 mb-2">
                <div class="col-12">
                    <fieldset class="m-0">
                        <legend><?php $theView->write($descriptions['buttom']['headline']); ?></legend>
                        <?php $theView->write($descriptions['buttom']['text']); ?>
                    </fieldset>
                </div>
            </div>
            <?php endif; ?>       
        </div>
    </div>
</div>