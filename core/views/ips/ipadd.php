<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row g-0 py-2">
    <div class="col-12">
        <fieldset class="py-2">
            <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
            <?php $theView->write('IPLIST_DESCRIPTION'); ?>
        </fieldset>
    </div>
</div>

<div class="row g-0 py-2">
    <div class="col-12">
        <fieldset class="py-2">
            <legend><?php $theView->write('IPLIST_ADDIP'); ?></legend>

            <div class="row py-2">
                <?php $theView->textInput('ipaddress')
                    ->setValue($object->getIpaddress())
                    ->setText('IPLIST_IPADDRESS')
                    ->setIcon('network-wired'); ?>
            </div>

            <div class="row py-2">
                <label class="col-12 col-sm-6 col-md-3 fpcm-ui-field-label-general">
                    <?php $theView->icon('lock'); ?>
                    <?php $theView->write('IPLIST_BLOCKTYPE'); ?>:
                </label>
                <div class="col-12 col-sm-6 col-md-9 fpcm ui-element-min-height-md fpcm-ui-input-wrapper-inner fpcm-ui-border-grey-medium fpcm-ui-border-radius-all">
                    <?php $theView->checkbox('nocomments')->setText('IPLIST_NOCOMMENTS')->setSelected($object->getNocomments())->setLabelClass('me-2'); ?>
                    <?php $theView->checkbox('nologin')->setText('IPLIST_NOLOGIN')->setSelected($object->getNologin())->setLabelClass('me-2'); ?>
                    <?php $theView->checkbox('noaccess')->setText('IPLIST_NOACCESS')->setSelected($object->getNoaccess()); ?>
                </div>
            </div>                        
        </fieldset>
    </div>
</div>