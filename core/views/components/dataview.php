<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if (!isset($headline) || !isset($dataViewId)) : ?>
<p><?php $theView->icon('ban')->setSize('lg')->setClass('fpcm-ui-important-text'); ?> <?php $theView->write(__FILE__.' required to assign variables "$headline" and "$dataViewId"!'); ?></p>
<?php else: ?>
<?php include $theView->getIncludePath('components/tabs.php'); ?>
<?php endif; ?>