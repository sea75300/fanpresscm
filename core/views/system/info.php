<?php /* @var $theView fpcm\view\viewVars */ ?>
<nav id="fpcm-id-scrollspy-info" class="navbar p-1 rounded-1 shadow-sm position-sticky top-0 bg-body-tertiary bg-opacity-75">
  <ul class="nav nav-pills">
    <li class="nav-item">
      <a class="nav-link" href="#fpcm-id-scroll-general"><?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#fpcm-id-scroll-version"><?php $theView->write('VERSION'); ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#fpcm-id-scroll-licence"><?php $theView->write('HL_HELP_LICENCE'); ?></a>
    </li>
    <?php if (trim($backdrop)) : ?>
    <li class="nav-item">
      <a class="nav-link" href="#fpcm-id-scroll-backdrop"><?php $theView->write('HL_HELP_BACKDROP'); ?></a>
    </li>
    <?php endif; ?>
  </ul>
</nav>

<div class="p-3">
    <div data-bs-spy="scroll" data-bs-target="#fpcm-id-scrollspy-info" data-bs-smooth-scroll="true">
        <h3 id="fpcm-id-scroll-general"><?php $theView->icon('question')->setSize('lg'); ?> <?php $theView->write('SYSTEM_HL_OPTIONS_GENERAL'); ?></h3>
        <?php print $content; ?>

        <h3 class="pt-5" id="fpcm-id-scroll-version"><?php $theView->icon('code-commit')->setSize('lg'); ?> <?php $theView->write('VERSION'); ?></h3>
        <p><?php print $theView->version; ?></p>

        <h3 class="pt-5" id="fpcm-id-scroll-licence"><?php $theView->icon('copyright')->setSize('lg'); ?> <?php $theView->write('HL_HELP_LICENCE'); ?></h3>
        <?php print nl2br($theView->escapeVal($licence)); ?>

        <?php if (trim($backdrop)) : ?>
        <p class="d-flex align-self-center align-items-center pt-5" id="fpcm-id-scroll-backdrop">
            <strong><?php $theView->icon('image')->setSize('lg'); ?> <?php $theView->write('HL_HELP_BACKDROP'); ?></strong>
            <?php $theView->linkButton('backdropCredits')->setText($backdrop)->setUrl($backdrop)->setTarget('_blank')->setRel('external')->overrideButtonType('link')->setClass('p-0 m-0'); ?>
        </p>
        <?php endif; ?>

    </div>
</div>