<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if (isset($diff) && is_array($diff)) : ?>
<div class="row g-0">
    <div class="col">
        <div class="card m-2">
            <div class="card-body">
                <h5 class="card-title">Language file diff</h5>
                <div class="list-group">
                <?php foreach ($diff as $var) : ?>
                    <div class="list-group-item">
                        <?php $theView->escape($var); ?>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>
<?php endif; ?>
<div class="fpcm-ui-dataview" id="fpcm-id-langedit-list">
    <div class="fpcm-ui-dataview-rows"></div>
</div>