<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            <?php $theView->write('INTEGRATION_TEXT_API') ?>: <?php $theView->escape($system_url); ?>
        </h5>
        <pre class="mb-0">
&lt;?php include_once "fanpress/fpcmapi.php"; ?&gt;
&lt;?php $api = new fpcmAPI(); ?&gt;</pre>
    </div>
</div>