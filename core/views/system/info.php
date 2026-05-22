<?php /* @var $theView fpcm\view\viewVars */ ?>
<nav id="fpcm-id-scrollspy-info" class="navbar p-1 rounded-1 shadow-sm position-sticky top-0 bg-body-tertiary bg-opacity-75">
  <ul class="nav nav-pills">
    <li class="nav-item">
      <a class="nav-link" href="#fpcm-id-scroll-general"><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#fpcm-id-scroll-version"><?php $theView->write('VERSION'); ?></a>
    </li>
    <?php if (trim($backdrop)) : ?>
    <li class="nav-item">
      <a class="nav-link" href="#fpcm-id-scroll-backdrop"><?php $theView->write('HL_HELP_BACKDROP'); ?></a>
    </li>
    <?php endif; ?>
    <li class="nav-item">
      <a class="nav-link" href="#fpcm-id-scroll-licence"><?php $theView->write('HL_HELP_LICENCE'); ?></a>
    </li>
  </ul>
</nav>

<div class="p-3">
    <div data-bs-spy="scroll" data-bs-target="#fpcm-id-scrollspy-info" data-bs-smooth-scroll="true">

        <div id="fpcm-id-scroll-general" class="mb-5">
            <h3 class="pb-3"><?php $theView->icon('question')->setSize('lg'); ?> <?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></h3>
            <?php print $content; ?>
        </div>

        <div id="fpcm-id-scroll-version" class="mb-5">
            <h3 class="pb-3"><?php $theView->icon('code-commit')->setSize('lg'); ?> <?php $theView->write('VERSION'); ?></h3>
            <p><?php print $theView->version; ?></p>
        </div>

        <?php if (trim($backdrop)) : ?>
        <div id="fpcm-id-scroll-backdrop" class="mb-5">
            <h3 class="pb-3"><?php $theView->icon('image')->setSize('lg'); ?> <?php $theView->write('HL_HELP_BACKDROP'); ?></h3>
            <?php $theView->linkButton('backdropCredits')->setText($backdrop)->setUrl($backdrop)->setTarget('_blank')->setRel('external')->overrideButtonType('link')->setClass('p-0 m-0'); ?>
        </div>
        <?php endif; ?>

        <div id="fpcm-id-scroll-licence">
            <h3 class="pb-3"><?php $theView->icon('copyright')->setSize('lg'); ?> <?php $theView->write('HL_HELP_LICENCE'); ?></h3>
            <?php print nl2br($theView->escapeVal($licence)); ?>
        </div>
    </div>
</div>