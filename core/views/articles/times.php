<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row no-gutters fpcm-ui-editor-metabox fpcm-ui-padding-md-top">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('GLOBAL_METADATA'); ?></legend>

            <div class="row no-gutters">
                <div class="col-sm-12 <?php if (!$isRevision) : ?>col-md-6 align-self-center <?php endif; ?> fpcm-ui-font-small">

                    <div class="<?php if ($isRevision) : ?>row <?php endif; ?> fpcm-ui-editor-metabox-left">

                        <?php if ($isRevision && $article->getImagepath()) : ?>
                        <div class="col-1 fpcm-ui-padding-none-lr">
                            <?php $theView->linkButton(uniqid('artimg'))->setText('EDITOR_ARTICLEIMAGE_SHOW')->setUrl($article->getImagepath())->setIcon('image')->setIconOnly(true)->setClass('fpcm-editor-articleimage'); ?>
                        </div>
                        <?php endif; ?>

                        <div class="<?php if ($isRevision) : ?>col-11 fpcm-ui-padding-none-lr <?php endif; ?>fpcm-ui-ellipsis">
                            <?php $theView->icon('calendar'); ?> <?php print $createInfo; ?><br>
                            <?php $theView->icon('clock', 'far'); ?> <?php print $changeInfo; ?>
                        </div>

                        <?php if ($isRevision) : ?>
                        <div class="col-12 fpcm-ui-padding-none-lr fpcm-ui-padding-md-tb">
                            <b><?php $theView->write('TEMPLATE_ARTICLE_SOURCES'); ?>:</b>
                            <?php print $article->getSources(); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                </div>

                <?php if ($isRevision) : ?><div class="row no-gutters"><?php endif; ?>
                <div class="col-12 <?php if (!$isRevision) : ?>col-md-6<?php endif; ?> fpcm-ui-align-right">
                    <?php print implode(PHP_EOL, $article->getMetaDataStatusIcons($showDraftStatus, $commentEnabledGlobal, $showArchiveStatus)); ?>
                </div>
                <?php if ($isRevision) : ?></div><?php endif; ?>
            </div>
            
        </fieldset>
    </div>
</div>


