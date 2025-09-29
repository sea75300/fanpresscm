<?php /* @var $theView fpcm\view\viewVars */ ?>

<div class="row">
    <div class="col">
        <p><?php $theView->alert('info')->setText('INTEGRATION_TEXT_START') ?></p>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="accordion" id="fpcm-accordion">
            <?php foreach ($items as $descr => $value) : ?>
            <div class="accordion-item" data-bs-target="#ac-item-<?php print md5($descr); ?>">
                <h2 class="accordion-header" id="head-<?php print md5($descr); ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#acc-<?php print md5($descr); ?>" aria-expanded="true" aria-controls="head-<?php print md5($descr); ?>">
                        <?php $theView->write($descr); ?>
                    </button>

                </h2>
                <div id="acc-<?php print md5($descr); ?>" class="accordion-collapse collapse" aria-labelledby="head-<?php print md5($descr); ?>" data-bs-parent="#ac-item-<?php print md5($descr); ?>">
                  <div class="accordion-body">
                    <?php include $theView->getIncludePath(sprintf("system/integration/%s.php", $value)); ?>
                  </div>
                </div>
            </div>

            <?php endforeach; ?>
        </div>
    </div>
</div>
