<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row g-0 gap-2">
    <div class="col">
        <div class="row my-2 row-cols-1 row-cols-xl-2">
            <div class="col">
                <?php $theView->select('system_editor')
                    ->setText('SYSTEM_OPTIONS_NEWS_EDITOR')
                    ->setOptions($editors)
                    ->setSelected(base64_encode($globalConfig->system_editor));
                ?>
            </div>

            <div class="col">
            <?php $theView->select('system_editor_fontsize')
                ->setText('SYSTEM_OPTIONS_NEWS_EDITOR_FONTSIZE')
                    ->setOptions($defaultFontsizes)
                    ->setSelected($globalConfig->system_editor_fontsize); ?>
            </div>
        </div>

        <div class="row my-2 row-cols-1 row-cols-xl-2">
            <div class="col">
                <?php $theView->boolSelect('articles_revisions')
                        ->setText('SYSTEM_OPTIONS_REVISIONS_ENABLED')
                        ->setSelected($globalConfig->articles_revisions); ?>
            </div>

            <div class="col">
                <?php $theView->select('articles_revisions_limit')
                        ->setText('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT')
                        ->setOptions($theView->translate('SYSTEM_OPTIONS_NEWS_REVISIONS_LIMIT_LIST'))
                        ->setSelected($globalConfig->articles_revisions_limit); ?>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="row my-2">
            <div class="col">
                <?php $theView->textarea('system_editor_css')
                        ->setValue($globalConfig->system_editor_css, ENT_QUOTES)
                        ->setClass('fpcm ui-textarea-medium ui-textarea-noresize')
                        ->setText('SYSTEM_OPTIONS_NEWS_EDITOR_CSS')
                        ->setPlaceholder('.your_css_class{}'); ?>
            </div>
        </div>
    </div>
</div>