<?php /* @var $theView \fpcm\view\viewVars */ ?>

<div class="row py-3">
    <div class="col">
        <div class="list-group list-group-horizontal-md my-2 my-md-0">
            <div class="list-group-item col-12 col-md-3 py-2">
                <label><?php $theView->icon('pen')->setSize('lg'); ?><?php $theView->write('ARTICLE_LIST_TITLE'); ?>:</label>
            </div>
            <div class="list-group-item col py-2 flex-grow-1 fpcm-ui-ellipsis">
            <?php if (trim($diffResultTitle)) : ?>
                <?php print $diffResultTitle; ?>
            <?php else : ?>
                <strong><?php print $theView->escape($revision->getTitle()); ?></strong>
            <?php endif; ?>
            </div>
        </div>
        <div class="list-group list-group-horizontal-md my-2 my-md-0">
            <div class="list-group-item col-12 col-md-3 py-2">
                <label><?php $theView->icon('tag')->setSize('lg'); ?><?php $theView->write('TEMPLATE_ARTICLE_CATEGORYTEXTS'); ?>:</label>
            </div>
            <div class="list-group-item col py-2 flex-grow-1 fpcm-ui-ellipsis">
            <?php if (trim($diffResultCategories)) : ?>
                <?php print $diffResultCategories; ?>
            <?php else : ?>
                <?php print $theView->escape(implode(', ', $categoriesArticle)); ?>
            <?php endif; ?>
            </div>
        </div>
        <div class="list-group list-group-horizontal-md my-2 my-md-0">
            <div class="list-group-item col-12 col-md-3 py-2">
                <label><?php $theView->icon('external-link-alt')->setSize('lg'); ?><?php $theView->write('TEMPLATE_ARTICLE_SOURCES'); ?>:</label>
            </div>
            <div class="list-group-item col py-2 flex-grow-1 fpcm-ui-ellipsis">
            <?php if (trim($diffResultSources)) : ?>
                <?php print $diffResultSources; ?>
            <?php else : ?>
                <?php print $revision->getSources() ? $revision->getSources() : '-'; ?>
            <?php endif; ?>
            </div>
        </div>
        <div class="list-group list-group-horizontal-md my-2 my-md-0">
            <div class="list-group-item col-12 col-md-3 py-2">
                <label><?php $theView->icon('external-link-alt')->setSize('lg'); ?><?php $theView->write('TEMPLATE_ARTICLE_ARTICLEIMAGE'); ?>:</label>
            </div>
            <div class="list-group-item col py-2 flex-grow-1 fpcm-ui-ellipsis">
            <?php if (trim($diffResultImagePath)) : ?>
                <?php print $diffResultImagePath; ?>
            <?php else : ?>
                <?php print $revision->getImagepath() ? $revision->getImagepath() : '-'; ?>
            <?php endif; ?>
            </div>
        </div>
        <div class="list-group list-group-horizontal-md my-2 my-md-0">
            <div class="list-group-item col-12 col-md-3 py-2">
                <label><?php $theView->icon('link')->setSize('lg'); ?><?php $theView->write('EDITOR_ARTICLE_ARTICLELINK', ['articleId' => $article->getId()]); ?>:</label>
            </div>
            <div class="list-group-item col py-2 flex-grow-1 fpcm-ui-ellipsis">
            <?php if (trim($diffResultUrl)) : ?>
                <?php print $diffResultUrl; ?>
            <?php else : ?>
                <?php print $revision->getNicePathString(); ?>
            <?php endif; ?>
            </div>
        </div>
        <div class="list-group list-group-horizontal-md my-2 my-md-0">
            <div class="list-group-item col-12 col-md-3 py-2">
                <label><?php $theView->icon('arrow-down-up-across-line')->setSize('lg'); ?><?php $theView->write('LABEL_FIELD_ARTICLE_RELATESTO', ['articleId' => $article->getId()]); ?>:</label>
            </div>
            <div class="list-group-item col py-2 flex-grow-1 fpcm-ui-ellipsis">
            <?php if (trim($diffResultRelation)) : ?>
                <?php print $diffResultRelation; ?>
            <?php else : ?>
                <?php print $revision->getRelatesTo(); ?>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 my-2">
        <div class="card">
            <div class="card-body">
            <?php if (trim($diffResultText)) : ?>
                <?php print $diffResultText; ?>
            <?php else : ?>
                    <?php print $revision->getContent(); ?>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-0">

    <div class="<?php $theView->defaultBoxHalf(); ?>">
        <div class="row g-0">
            <div class="col-12">
                <fieldset class="my-2">
                    <legend class="fs-6 bg-secondary"><?php $theView->write('GLOBAL_METADATA'); ?></legend>

                    <div class="row g-0 my-2 fs-6">
                        <div class="col-12">

                            <div class="row mb-2">
                                <div class="col-12">
                                    <?php print implode(' ', $revision->getMetaDataStatusIcons(true, true, true)); ?>
                                </div>
                            </div>

                            <div class="row mb-1 row-cols-2">
                                <div class="col">
                                    <?php $theView->icon('calendar')->setSize('lg'); ?>
                                    <strong><?php $theView->write('GLOBAL_AUTHOR_EDITOR'); ?>:</strong>
                                </div>
                                <div class="col">
                                    <?php print $revisionCreate; ?>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col">
                                    <?php $theView->icon('clock', 'far')->setSize('lg'); ?>
                                    <strong><?php $theView->write('GLOBAL_LASTCHANGE'); ?>:</strong>
                                </div>
                                <div class="col">
                                    <?php print $revisionChange; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="<?php $theView->defaultBoxHalf(); ?> ps-0 ps-md-2">
        <div class="row g-0">
            <div class="col-12">
                <fieldset class="my-2">
                    <legend class="fs-6"><?php $theView->write('GLOBAL_METADATA'); ?></legend>

                    <div class="row g-0 my-2 fs-6">
                        <div class="col-12">

                            <div class="row mb-2">
                                <div class="col-12">
                                    <?php print implode(' ', $article->getMetaDataStatusIcons(true, true, true)); ?>
                                </div>
                            </div>

                            <div class="row mb-1 row-cols-2">
                                <div class="col">
                                    <?php $theView->icon('calendar')->setSize('lg'); ?>
                                    <strong><?php $theView->write('GLOBAL_AUTHOR_EDITOR'); ?>:</strong>
                                </div>
                                <div class="col">
                                    <?php print $articleCreate; ?>
                                </div>
                            </div>

                            <div class="row mb-1 row-cols-2">
                                <div class="col">
                                    <?php $theView->icon('clock', 'far')->setSize('lg'); ?>
                                    <strong><?php $theView->write('GLOBAL_LASTCHANGE'); ?>:</strong>
                                </div>
                                <div class="col">
                                    <?php print $articleChange; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

</div>