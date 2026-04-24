<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="d-flex justify-content-end">
    <?php $theView->button('openTopHelp')
            ->setText('HELP_TOP_MENU_FUNCTIONS')
            ->setIcon('bars-staggered')
            ->overrideButtonType('outline-secondary')
            ->setClass('btn-sm')
            ->setData([
                'bs-toggle' => 'collapse',
                'bs-target' => '#' . $theView->addIDPrefix('help-top-menu')
            ])
            ->setAria(['expanded' => 'false']);
    ?>
</div>

<div class="fpcm ui-help-content">

    <div class="collapse mt-1" id="<?php print $theView->addIDPrefix('help-top-menu'); ?>">
      <div class="card card-body">
        <?php print $topMenuHelp.PHP_EOL.PHP_EOL; ?>
      </div>
    </div>

    <hr>

    <div class="card card-body">
        <?php print $content.PHP_EOL.PHP_EOL; ?>
    </div>
</div>