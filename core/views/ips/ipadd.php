<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="row g-0 py-2">
    <div class="col-12">
        <fieldset class="py-2">
            <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
            <p class="mx-2"><?php $theView->write('IPLIST_DESCRIPTION'); ?></p>
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <fieldset class="m-3 fpcm-ui-border-grey-medium">
            <legend><?php $theView->write('IPLIST_ADDIP'); ?></legend>
            
            <div class="row">
                <?php $theView->textInput('ipaddress')
                    ->setValue($object->getIpaddress())
                    ->setText('IPLIST_IPADDRESS')
                    ->setIcon('network-wired'); ?>
            </div>

            <div class="row">
                
                <div class="col-form-label col-12 col-sm-6 col-md-3">
                    <?php $theView->icon('lock'); ?> <span class="fpcm-ui-label ps-1"> <?php $theView->write('IPLIST_BLOCKTYPE'); ?></span>
                </div>
                
                <div class=" col-12 col-sm-6 col-md-9">
                    <?php $theView->checkbox('nocomments')->setText('IPLIST_NOCOMMENTS')->setSelected($object->getNocomments())->setSwitch(true); ?>
                    <?php $theView->checkbox('nologin')->setText('IPLIST_NOLOGIN')->setSelected($object->getNologin())->setSwitch(true); ?>
                    <?php $theView->checkbox('noaccess')->setText('IPLIST_NOACCESS')->setSelected($object->getNoaccess())->setSwitch(true); ?>
                </div>
                
            </div>

        </fieldset>
    </div>
</div>
