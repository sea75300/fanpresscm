<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row">

    <div class="col">
        <?php $theView->textarea('text')->setText('texts')->setLabelTypeFloat()->setClass('fpcm ui-textarea-medium')->setValue($texts, ENT_QUOTES); ?>
    </div>

</div>