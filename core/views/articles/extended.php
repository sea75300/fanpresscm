<?php
/* @var $theView \fpcm\view\viewVars */
/* @var $article \fpcm\model\articles\article */
?>
<fieldset class="py-3">

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="row g-0">
                <div class="col-form-label pe-3  col-12 col-sm-6 col-md-4 mb-2">
                    <?php $theView->icon('cogs')->setSize('lg'); ?> <span class="fpcm-ui-label ps-1"> <?php $theView->write('GLOBAL_EXTENDED'); ?></span>
                </div>

                <div class="col">
                    <div class="list-group">
                    <?php if (!$article->getArchived()) : ?>
                        <div class="list-group-item">
                            <?php $theView->checkbox('article[pinned]')->setText('EDITOR_PINNED')->setSelected($article->getPinned())->setClass('fpcm-ui-editor-metainfo-checkbox')->setData(['icon' => 'pinned'])->setSwitch(true); ?>
                        </div>
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
</fieldset>

<hr>

<?php if ($changeAuthor) : ?>
<fieldset class="py-3">

        <div class="row">
            <div class="col-12 col-md-8">
        <?php $theView->select('article[author]')
                ->setOptions($changeuserList)
                ->setSelected($article->getCreateuser())
                ->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setText('EDITOR_CHANGEAUTHOR')
                ->setIcon('users')
                ->setSize('lg'); ?>
            </div>
        </div>
</fieldset>

<hr>
<?php endif; ?>

<fieldset class="py-3">
    <div class="row">
        <div class="col-12 col-md-8">
            <?php $theView->textInput('article[sources]')
                    ->setPlaceholder('http://')
                    ->setText('TEMPLATE_ARTICLE_SOURCES')
                    ->setValue($article->getSources())
                    ->setIcon('external-link-alt')
                    ->setSize('lg'); ?>

        </div>
        <div class="col-12 col-md-1">
            <?php $theView->button('editsources')->setText('SYSTEM_OPTIONS_NEWS_SOURCESLIST')->setIcon('pen')->setIconOnly(); ?>
        </div>  
    </div>
</fieldset>

<fieldset class="py-3">
    <div class="row">
        <div class="col-12 col-md-8">
            <?php $theView->textInput('article[imagepath]')
                ->setType('url')
                ->setPlaceholder('http://')
                ->setText('TEMPLATE_ARTICLE_ARTICLEIMAGE')
                ->setValue($article->getImagepath())
                ->setMaxlenght(512)
                ->setIcon('image')
                ->setSize('lg'); ?>
        </div>        
        <div class="col-12 col-md-1">
            <?php $theView->button('insertarticleimg', 'insertarticleimg')->setText('HL_FILES_MNG')->setIcon('folder-open')->setIconOnly(); ?>
        </div>        
    </div>
</fieldset>

<?php if ($editorMode && $urlRewrite) : ?>
<fieldset class="py-3">
    <div class="row">
        <div class="col-12 col-md-8">
            <?php $theView->textInput('article[url]')
                ->setType('text')
                ->setPlaceholder($article->getNicePathString())
                ->setText('EDITOR_ARTICLE_ARTICLELINK', ['articleId' => $article->getId()])
                ->setValue($article->getUrl())
                ->setMaxlenght(512)
                ->setIcon('link')
                ->setSize('lg'); ?>
        </div>
    </div>
</fieldset>

<?php endif; ?>

<hr>

<?php if (!$editorMode || $article->getPostponed()) : ?>
<fieldset class="py-3">
    <div class="row">
        <div class="col">
            <?php $theView->checkbox('article[postponed]')
                    ->setText('EDITOR_POSTPONETO')
                    ->setSelected($article->getPostponed())
                    ->setClass('fpcm-ui-editor-metainfo-checkbox')
                    ->setData(['icon' => 'postponed'])
                    ->setSwitch(true); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-8">

            <div class="input-group">
                <label class="col-form-label pe-3  col-12 col-sm-6 col-md-4" for="article_postponedate">
                    <?php $theView->icon('calendar-plus')->setSize('lg'); ?> <?php $theView->write('EDITOR_POSTPONED_DATE'); ?>
                </label>
                <input class="form-control" name="article[postponedate]" id="article_postponedate" type="date" mindate="0d" maxdate="+2m" value="<?php print $theView->dateText($postponedTimer, 'Y-m-d'); ?>">
                <input class="form-control" name="article[postponehour]" type="number" min="0" max="23" value="<?php print $theView->dateText($postponedTimer, 'H'); ?>">
                <input class="form-control" name="article[postponeminute]" type="number" class="form-control" min="0" max="59" value="<?php print $theView->dateText($postponedTimer, 'i'); ?>">
            </div>
        </div>
    </div>
</fieldset>
<?php endif; ?>

<?php if ($showTwitter && !empty($twitterReplacements) && !empty($twitterTplPlaceholder)) : ?>
<fieldset class="py-3">
    <div class="row">
        <div class="col">
            <?php $theView->checkbox('article[tweet]')
                    ->setText('EDITOR_TWEET_ENABLED')
                    ->setSelected($article->tweetCreationEnabled())
                    ->setSwitch(true); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-8">
                <?php $theView->textInput('article[tweettxt]')
                        ->setPlaceholder($twitterTplPlaceholder)
                        ->setText('EDITOR_TWEET_TEXT')
                        ->setValue('')
                        ->setSize(280)
                        ->setIcon('twitter', 'fab')
                        ->setSize('lg'); ?>
        </div>

        <div class="col-12 col-md-4">
            <div class="row">

                <div class="col align-self-center">
                    <?php $theView->dropdown('twitterReplacements')
                            ->setOptions($twitterReplacements)
                            ->setSelected('')
                            ->setText('TEMPLATE_REPLACEMENTS')
                            ->setIcon('square-plus')
                            ->setIconOnly(); ?>
                </div>

            </div>
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