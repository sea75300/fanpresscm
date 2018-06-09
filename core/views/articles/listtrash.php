<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-full-height">
    <div class="col-12">
        <div class="fpcm-content-wrapper fpcm-ui-full-height">
            <div class="fpcm-ui-tabs-general">
                <ul class="fpcm-tabs-articles-headers">
                    <li><a href="#tabs-article-trash"><?php $theView->write('ARTICLES_TRASH'); ?></a></li>
                </ul>

                <div id="tabs-article-trash">
                    <?php include $theView->getIncludePath('articles/lists/trash.php'); ?>
                </div>

            </div>
        </div>
    </div>
</div>