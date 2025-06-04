<?php
/* @var $theView \fpcm\view\viewVars */
/* @var $article \fpcm\model\articles\article */
?>
<fieldset class="py-3">

    <div class="row">
        <div class="col-12 col-md-10 col-lg-6">
            <div class="row g-0">
                
                <div class="input-group">
                    <label class="input-group-text col-12 col-md-6 col-lg-3">
                        <?php $theView->icon('cogs'); ?>
                        <span class="fpcm-ui-label ps-1"><?php $theView->write('GLOBAL_EXTENDED'); ?></span>
                    </label>                 

                    <div class="form-control p-0">
                        <div class="list-group list-group-flush">
                        <?php if (!$article->getArchived()) : ?>
                            <div class="list-group-item">
                                <?php $theView->checkbox('article[draft]')->setText('EDITOR_DRAFT')->setSelected($article->getDraft())->setClass('fpcm-ui-editor-metainfo-checkbox')->setData(['icon' => 'draft'])->setSwitch(true); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($commentEnabledGlobal) : ?>
                            <div class="list-group-item">
                                <?php $theView->checkbox('article[comments]')->setText('EDITOR_COMMENTS')->setSelected($article->getComments())->setClass('fpcm-ui-editor-metainfo-checkbox')->setData(['icon' => 'comments'])->setSwitch(true); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!$approvalRequired) : ?>
                            <div class="list-group-item">
                                <?php $theView->checkbox('article[approval]')->setText('EDITOR_STATUS_APPROVAL')->setSelected($article->getApproval())->setClass('fpcm-ui-editor-metainfo-checkbox')->setData(['icon' => 'approval'])->setSwitch(true); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($editorMode && $theView->permissions->article->archive) : ?>
                            <div class="list-group-item">
                                <?php $theView->checkbox('article[archived]')->setText('EDITOR_ARCHIVE')->setSelected($article->getArchived())->setClass('fpcm-ui-editor-metainfo-checkbox')->setData(['icon' => 'archived'])->setSwitch(true); ?>
                            </div>
                        <?php endif; ?>
                        </div>

                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
</fieldset>

<hr>

<?php if (!$article->getArchived()) : ?>
<fieldset class="py-3">

    <div class="row">
        <div class="col-12 col-md-10 col-lg-6">
                <div class="input-group">
                    <label class="input-group-text col-12 col-md-6 col-lg-3">
                        <?php $theView->icon('thumbtack'); ?>
                        <span class="fpcm-ui-label ps-1"><?php $theView->write('EDITOR_PINNED'); ?></span>
                    </label>
                    <div class="input-group-text">
                        <?php $theView->checkbox('article[pinned]')
                            ->setSelected($article->getPinned())
                            ->setClass('fpcm-ui-editor-metainfo-checkbox')
                            ->setData(['icon' => 'pinned'])
                            ->setSwitch(true); ?>
                    </div>
                    <div class="form-floating">
                        <input class="form-control" name="article[pinned_until]" id="article_pinned_until" type="date" mindate="+1d" maxdate="+4w" value="<?php print $theView->dateText($pinnedTimer, 'Y-m-d'); ?>">
                        <label for="article_postponedate">
                            <?php $theView->icon('calendar-plus'); ?> <?php $theView->write('EDITOR_PINNED_DATE'); ?>
                        </label>
                    </div>
                </div>
        </div>
    </div>
</fieldset>

<?php endif; ?>

<?php if (!$editorMode || $article->getPostponed()) : ?>

<hr>

<fieldset class="py-3">

    <div class="row">
        <div class="col-12 col-md-10 col-lg-6">
            <div class="input-group">
                <label class="input-group-text col-12 col-md-6 col-lg-3">
                    <?php $theView->icon('calendar-plus'); ?>
                    <span class="fpcm-ui-label ps-1"><?php $theView->write('EDITOR_POSTPONETO'); ?></span>
                </label>
                <div class="input-group-text">
                    <?php $theView->checkbox('article[postponed]')
                        ->setSelected($article->getPostponed())
                        ->setClass('fpcm-ui-editor-metainfo-checkbox')
                        ->setData(['icon' => 'postponed'])
                        ->setSwitch(true); ?>
                </div>
                <div class="form-floating">
                    <input class="form-control" name="article[postponedate]" id="article_postponedate" type="date" mindate="0d" maxdate="+2m" value="<?php print $theView->dateText($postponedTimer, 'Y-m-d'); ?>">
                    <label for="article_postponedate">
                        <?php $theView->icon('calendar-plus'); ?> <?php $theView->write('EDITOR_POSTPONED_DATE'); ?>
                    </label>
                </div>
                <div class="form-floating">
                    <input class="form-control" name="article[postponetime]" type="time" value="<?php print $theView->dateText($postponedTimer, 'H:i'); ?>">
                    <label for="article_postponedate">
                        <?php $theView->icon('clock'); ?> <?php $theView->write('EDITOR_POSTPONED_DATETIME'); ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<?php endif; ?>

<?php if ($changeAuthor) : ?>
<fieldset class="py-3">

        <div class="row">
            <div class="col-12 col-md-10 col-lg-6">
            <?php $theView->select('article[author]')
                ->setOptions($changeuserList)
                ->setSelected($article->getCreateuser())
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setText('EDITOR_CHANGEAUTHOR')
                ->setIcon('users')
                ->setLabelTypeFloat()
                ->setBottomSpace(''); ?>
            </div>
        </div>
</fieldset>

<hr>

<?php endif; ?>

<fieldset class="py-3">
    <div class="row">
        <div class="col-12 col-md-10 col-lg-6">
            <div class="input-group">
                <?php $theView->textInput('article[sources]')
                    ->setPlaceholder('http://')
                    ->setText('TEMPLATE_ARTICLE_SOURCES')
                    ->setValue($article->getSources())
                    ->setIcon('external-link-alt')
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>

                <?php $theView->button('editsources')->setText('SYSTEM_OPTIONS_NEWS_SOURCESLIST')->setIcon('pen')->setIconOnly(); ?>
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="py-3">
    <div class="row">
        <div class="col-12 col-md-10 col-lg-6">
            <div class="input-group">
                <?php $theView->textInput('article[imagepath]')
                    ->setType('url')
                    ->setPlaceholder('http://')
                    ->setText('TEMPLATE_ARTICLE_ARTICLEIMAGE')
                    ->setValue($article->getImagepath())
                    ->setMaxlenght(512)
                    ->setIcon('image')
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''); ?>

                <?php $theView->button('insertarticleimg', 'insertarticleimg')->setText('HL_FILES_MNG')->setIcon('folder-open')->setIconOnly(); ?>
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="py-3">
    <div class="row">
        <div class="col-12 col-md-10 col-lg-6">
            <?php $theView->textInput('article[relatesto]', 'fpcm-id-articles-relates-to')
                ->setValue($article->getRelatesTo())
                ->setIcon('arrow-down-up-across-line')
                ->setLabelTypeFloat()
                ->setBottomSpace(''); ?>

        </div>
    </div>
</fieldset>

<?php if ($editorMode && $urlRewrite) : ?>
<fieldset class="py-3">
    <div class="row">
        <div class="col-12 col-md-10 col-lg-6">
            <?php $theView->textInput('article[url]')
                ->setType('text')
                ->setPlaceholder($article->getNicePathString())
                ->setText('EDITOR_ARTICLE_ARTICLELINK', ['articleId' => $article->getId()])
                ->setValue($article->getUrl())
                ->setMaxlenght(512)
                ->setIcon('link')
                ->setLabelTypeFloat()
                ->setBottomSpace(''); ?>
        </div>
    </div>
</fieldset>
<?php endif; ?>

<?php if ($showShares && count($shares)) : ?>

<hr>

<fieldset class="py-3">
    <legend><?php $theView->write('EDITOR_SHARES'); ?></legend>
    <?php foreach ($shares as $share) : ?>
    <div class="row g-0">
        <div class="col-2 col-lg-1"><?php print $share->getIcon(); ?></div>
        <div class="col-6 col-lg-2 align-self-center"><?php print $share->getDescription(); ?>:</div>
        <div class="col-4 col-lg-1 align-self-center text-center"><?php print $share->getSharecount(); ?></div>
        <div class="col-12 col-lg-auto align-self-center"><?php $theView->icon('clock', 'far')->setText('EDITOR_SHARES_LAST'); ?> <?php $theView->dateText($share->getLastshare()); ?></div>
    </div>
    <?php endforeach; ?>
</fieldset>
<?php endif; ?>