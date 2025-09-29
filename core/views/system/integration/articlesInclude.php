<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row g-0 gap-2">
    <div class="col">
    <?php $theView->textInput('article[count]')
        ->setValue($articleCount)
        ->setText('SYSTEM_OPTIONS_NEWSSHOWLIMIT')
        ->setLabelTypeFloat(); ?>
    </div>
    <div class="col">
    <?php $theView->select('article[category]')
        ->setOptions($categories)
        ->setText('INTEGRATION_TEXT_SHOW_ARTICLE_CATEGORY')
        ->setLabelTypeFloat(); ?>
    </div>
    <div class="col">
    <?php $theView->select('article[template]')
        ->setOptions($templates)
        ->setText('SYSTEM_OPTIONS_ACTIVENEWSTEMPLATE')
        ->setLabelTypeFloat(); ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            <?php $theView->write('INTEGRATION_TEXT_SHOW_ARTICLE') ?>
        </h5>
        <pre class="mb-0">
&lt;div class=&quot;fpcm-pub-content&quot;&gt;
&lt;?php
$api->showArticles(<span id="functionParams"></span>);
?&gt;
&lt;/div&gt;</pre>
    </div>
</div>