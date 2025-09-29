<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            <?php $theView->write('INTEGRATION_TEXT_RSS_FEED') ?>
        </h5>
        <pre class="mb-0">&lt;a href=&quot;<?php print $theView->basePath; ?>fpcm/feed&quot; class=&quot;fpcm-pub-rssfeed-link&quot;&gt;RSS-Feed&lt;/a&gt;</pre>
    </div>
</div>