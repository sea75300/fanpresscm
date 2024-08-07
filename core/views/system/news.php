<?php /* @var $theView \fpcm\view\viewVars */ ?>
<fieldset class="mb-2">
    <legend><?php $theView->write('HL_FRONTEND'); ?></legend>

    <div class="row my-2">
        <div class="col-12 col-md-8">
        <?php $theView->select('articles_limit')
                ->setText('SYSTEM_OPTIONS_NEWSSHOWLIMIT')
                ->setOptions($articleLimitList)
                ->setSelected($globalConfig->articles_limit); ?>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
            <div class="row g-0">
                <div class="col flex-grow-1">
                <?php $theView->select('articles_template_active')
                    ->setText('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATE')
                    ->setOptions($articleTemplates)
                    ->setSelected($globalConfig->articles_template_active); ?>
                </div>
                <div class="col-auto align-self-center mx-3 mb-3">
                    <?php $theView->linkButton('system_url_link')
                        ->setText('GLOBAL_EDIT')
                        ->setUrl(sprintf('%s&module=templates/templates&rg=0', $theView->basePath))
                        ->setIcon('file-pen')
                        ->setIconOnly(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row my-2">
        <div class="col-12 col-md-8">
            <div class="row g-0">
                <div class="col flex-grow-1">
                <?php $theView->select('article_template_active')
                    ->setText('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATESINGLE')
                    ->setOptions($articleTemplates)
                    ->setSelected($globalConfig->article_template_active); ?>
                </div>
                <div class="col-auto align-self-center mx-3 mb-3">
                    <?php $theView->linkButton('system_url_link')
                        ->setText('GLOBAL_EDIT')
                        ->setUrl(sprintf('%s&module=templates/templates&rg=1', $theView->basePath))
                        ->setIcon('file-pen')
                        ->setIconOnly(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0 my-2">
        <div class="col-12 col-md-8">
            <div class="row row-cols-1 row-cols-xl-2">
                <div class="col">
                    <?php $theView->select('articles_sort')
                        ->setText('SYSTEM_OPTIONS_NEWS_SORTING')
                        ->setOptions($sorts)
                        ->setSelected($globalConfig->articles_sort); ?>
                </div>

                <div class="col">
                    <?php $theView->select('articles_sort_order')
                        ->setText('SYSTEM_OPTIONS_NEWS_SORTING_ORDER')
                        ->setOptions($sortsOrders)
                        ->setSelected($globalConfig->articles_sort_order); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0 my-2">
        <div class="col-12 col-md-8">
            <div class="row row-cols-1 row-cols-xl-2">
                <div class="col">
                    <?php $theView->boolSelect('system_show_share')->setText('SYSTEM_OPTIONS_NEWSSHOWSHARELINKS')->setSelected($globalConfig->system_show_share); ?>
                </div>

                <div class="col">
                    <?php $theView->boolSelect('system_share_count')->setText('SYSTEM_OPTIONS_NEWSSHARECOUNT')->setSelected($globalConfig->system_share_count); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-0 my-2">
        <div class="col-12 col-md-8">
            <div class="row row-cols-1 row-cols-xl-2">
                <div class="col">
                    <?php $theView->boolSelect('articles_link_urlrewrite')->setText('SYSTEM_OPTIONS_NEWS_URLREWRITING')->setSelected($globalConfig->articles_link_urlrewrite); ?>
                </div>

                <div class="col">
                    <?php $theView->boolSelect('articles_rss')->setText('SYSTEM_OPTIONS_NEWS_ENABLEFEED')->setSelected($globalConfig->articles_rss); ?>
                </div>
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="mb-2">
    <legend><?php $theView->write('HL_ARCHIVE'); ?></legend>

    <div class="row g-0 my-2">
        <div class="col-12 col-md-8">
            <div class="row row-cols-1 row-cols-xl-2">
                <div class="col">
                    <?php $theView->boolSelect('articles_archive_show')->setText('SYSTEM_OPTIONS_ARCHIVE_LINK')->setSelected($globalConfig->articles_archive_show); ?>
                </div>

                <div class="col">
                    <div class="row g-0">
                        <div class="col flex-grow-1">
                        <?php $theView->dateTimeInput('articles_archive_datelimit')
                                ->setValue($globalConfig->articles_archive_datelimit ? $theView->dateText($globalConfig->articles_archive_datelimit, 'Y-m-d') : '')
                                ->setText('SYSTEM_OPTIONS_NEWS_ARCHIVELIMIT')
                                ->setData(['maxDate' => '-3m']); ?>
                        </div>
                        <div class="col-auto align-self-center mx-3 mb-3">
                            <?php $theView->shorthelpButton('dtmask')
                                    ->setText('SYSTEM_OPTIONS_NEWS_ARCHIVELIMIT_EMPTY'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</fieldset>