<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="border-top border-5 border-primary">
    <div class="fpcm-ui-dataview">
        <div class="fpcm-ui-dataview-rows">
            <?php foreach ($langVars as $key => $value) : ?>

                <div class="row py-2 border-bottom border-1 border-secondary">

                    <div class="col-auto align-self-center">
                        <?php $theView->button('edit'. strtolower($key))->setData([
                            'var' => $key,
                            'dest' => strtolower($key)
                        ])
                        ->setText('GLOBAL_EDIT')
                        ->setIcon('edit')
                        ->setIconOnly()
                        ->setClass('fpcm-language-edit'); ?>

                        <?php $theView->button('delete'. strtolower($key))
                        ->setText('GLOBAL_DELETE')
                        ->setIcon('trash')
                        ->setIconOnly()
                        ->setClass('fpcm-language-delete'); ?>
                    </div>

                    <div class="col-4 align-self-center fpcm-ui-ellipsis">
                        <?php print $theView->escapeVal($key); ?>
                    </div>

                    <div class="col-6 align-self-center fpcm-ui-ellipsis" id="lang_descr_<?php print strtolower($key); ?>">
                        <?php print $theView->escapeVal( (is_array($value) ? serialize($value) : str_replace(PHP_EOL, fpcm\classes\language::VARTEXT_NEWLINE, $value)) ); ?>
                    </div>

                    <?php $theView->textarea('lang['.$key.']', 'lang_'. strtolower($key))->setValue( (is_array($value) ? serialize($value) : str_replace(PHP_EOL, fpcm\classes\language::VARTEXT_NEWLINE, $value) ) )->setClass('fpcm ui-hidden');  ?>
                </div>

            <?php endforeach; ?>
        </div>    
    </div>
</div>