<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-full-height">
    <div class="col-12">
        <div class="fpcm-content-wrapper fpcm-ui-full-height">
            <div class="fpcm-ui-tabs-general">
                <ul>
                    <li><a href="#tabs-comments-active"><?php $theView->write('ARTICLES_TRASH'); ?></a></li>
                </ul>            

                <div id="tabs-comments-active">
                    <div id="fpcm-dataview-commenttrash"></div>
                </div>
            </div>
        </div>
    </div>
</div>