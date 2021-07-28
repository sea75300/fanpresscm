<?php /* @var $theView \fpcm\view\viewVars */ ?>

<div class="row g-0">
    <div class="col-12 fpcm-ui-ellipsis">    
    <?php if (trim($diffResultTitle)) : ?>
        <div class="m-2">
            <?php print $diffResultTitle; ?>
        </div>
    <?php else : ?>
        <strong class="d-block m-2"><?php print $theView->escape($revision->getTitle()); ?></strong>
    <?php endif; ?>
    </div>
</div>

<div class="row g-0">
    <div class="<?php $theView->defaultBoxHalf(); ?>">
        <fieldset>
            <legend class="fpcm-ui-font-small"><?php $theView->write('TEMPLATE_ARTICLE_CATEGORYTEXTS'); ?></legend>
            
            <div class="row">
                <div class="col-12 mb-2">
                    <?php print $theView->escape(implode(', ', $categoriesRevision)); ?>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="<?php $theView->defaultBoxHalf(); ?> ps-0 ps-md-2">
        <fieldset>
            <legend class="fpcm-ui-font-small"><?php $theView->write('TEMPLATE_ARTICLE_CATEGORYTEXTS'); ?></legend>
            
            <div class="row">
                <div class="col-12 mb-2">
                    <?php print $theView->escape(implode(', ', $categoriesArticle)); ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div class="row g-0">
    <div class="<?php $theView->defaultBoxHalf(); ?>">
        <fieldset>
            <legend class="fpcm-ui-font-small"><?php $theView->write('TEMPLATE_ARTICLE_SOURCES'); ?></legend>
            
            <div class="row">
                <div class="col-12 mb-2">
                    <?php print $revision->getSources() ? $revision->getSources() : '-'; ?>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="<?php $theView->defaultBoxHalf(); ?> ps-0 ps-md-2">
        <fieldset>
            <legend class="fpcm-ui-font-small"><?php $theView->write('TEMPLATE_ARTICLE_SOURCES'); ?></legend>
            
            <div class="row">
                <div class="col-12 mb-2">
                    <?php print $article->getSources() ? $article->getSources() : '-'; ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div class="row g-0">
    <div class="<?php $theView->defaultBoxHalf(); ?>">
        <fieldset>
            <legend class="fpcm-ui-font-small"><?php $theView->write('TEMPLATE_ARTICLE_ARTICLEIMAGE'); ?></legend>
            
            <div class="row">
                <div class="col-12 mb-2">
                    <?php print $revision->getImagepath() ? $revision->getImagepath() : '-'; ?>
                </div>
            </div>

        </fieldset>
    </div>
    <div class="<?php $theView->defaultBoxHalf(); ?> ps-0 ps-md-2">
        <fieldset>
            <legend class="fpcm-ui-font-small"><?php $theView->write('TEMPLATE_ARTICLE_ARTICLEIMAGE'); ?></legend>
            
            <div class="row">
                <div class="col-12 mb-2">
                    <?php print $article->getImagepath() ? $article->getImagepath() : '-'; ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div class="row g-0">
    <div class="col-12 fpcm-ui-ellipsis">
        <div class="row">
            <div class="col-12 my-2">
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
                    <legend class="fpcm-ui-font-small"><?php $theView->write('GLOBAL_METADATA'); ?></legend>

                    <div class="row g-0 my-2 fpcm-ui-font-small">
                        <div class="col-12 col-md-6">

                            <div class="row mb-1">
                                <div class="col-auto">
                                    <?php $theView->icon('calendar')->setSize('lg'); ?>
                                    <strong><?php $theView->write('GLOBAL_AUTHOR_EDITOR'); ?>:</strong>
                                </div>
                                <div class="col">
                                    <?php print $revisionCreate; ?>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-auto">
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
                    <legend class="fpcm-ui-font-small"><?php $theView->write('GLOBAL_METADATA'); ?></legend>

                    <div class="row g-0 my-2 fpcm-ui-font-small">
                        <div class="col-12 col-md-6">

                            <div class="row mb-1">
                                <div class="col-auto">
                                    <?php $theView->icon('calendar')->setSize('lg'); ?>
                                    <strong><?php $theView->write('GLOBAL_AUTHOR_EDITOR'); ?>:</strong>
                                </div>
                                <div class="col">
                                    <?php print $articleCreate; ?>
                                </div>
                            </div>

                            <div class="row mb-1">
                                <div class="col-auto">
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