<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row fpcm-ui-padding-md-tb">
    
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-left">
        <div class="row fpcm-ui-editor-metabox fpcm-ui-padding-md-tb">
            <?php
                $tmpArticle = $article;
                $article    = $revisionArticle;
                $createInfo = $createInfoOrg;
                $changeInfo = $changeInfoOrg;
            ?>
            <div class="col-sm-12 fpcm-ui-padding-none-lr">
                <?php include $theView->getIncludePath('articles/times.php'); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-right">
        <div class="row fpcm-ui-editor-metabox fpcm-ui-padding-md-tb">
            <?php
                $article    = $tmpArticle;
                $tmpArticle = null;
                $createInfo = $createInfoRev;
                $changeInfo = $changeInfoRev;
            ?>        
            <div class="col-sm-12 fpcm-ui-padding-none-lr">
                <?php include $theView->getIncludePath('articles/times.php'); ?>
            </div>
        </div>
    </div>
    
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-left">
        <h3><?php print $theView->escape($article->getTitle()); ?></h3>
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-right">
        <h3><?php print $theView->escape($revisionArticle->getTitle()); ?></h3>
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-left">
        <div class="fpcm-ui-controlgroup fpcm-ui-editor-categories fpcm-ui-editor-categories-revisiondiff">
            <?php foreach ($categories as $value => $key) : ?>
            <?php $theView->checkbox('article[categories][revision]', 'rcat'.$value)->setValue($value)->setText($key->getName())->setSelected(in_array($value, $revisionArticle->getCategories())); ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 fpcm-ui-padding-none-right">
        <div class="fpcm-ui-controlgroup fpcm-ui-editor-categories fpcm-ui-editor-categories-revisiondiff">
            <?php foreach ($categories as $value => $key) : ?>
            <?php $theView->checkbox('article[categories][current]', 'ccat'.$value)->setValue($value)->setText($key->getName())->setSelected(in_array($value, $article->getCategories())); ?>
            <?php endforeach; ?>
        </div> 
    </div>
</div>

<div class="row fpcm-ui-padding-md-tb">
    <div class="col-sm-12 fpcm-ui-padding-none-lr">
        <?php print html_entity_decode($textDiff); ?>
    </div>
</div>