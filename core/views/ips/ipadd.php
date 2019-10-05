<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general">
        <ul>
            <li><a href="#tabs-ip"><?php $theView->write('IPLIST_ADDIP'); ?></a></li>
        </ul>            

        <div id="tabs-ip">          

            <div class="row no-gutters fpcm-ui-padding-md-tb">
                <div class="col-12">
                    <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
                        <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
                        <?php $theView->write('IPLIST_DESCRIPTION'); ?>
                    </fieldset>
                </div>
            </div>

            <div class="row no-gutters fpcm-ui-padding-md-tb">
                <div class="col-12">
                    <fieldset class="fpcm-ui-margin-none-left fpcm-ui-margin-none-right fpcm-ui-margin-md-top">
                        <legend><?php $theView->write('IPLIST_ADDIP'); ?></legend>

                        <div class="row fpcm-ui-padding-md-tb no-gutters">
                            <div class="col-12">
                                <div class="row">
                                    <?php $theView->textInput('ipaddress')
                                        ->setWrapper(false)
                                        ->setValue($object->getIpaddress())
                                        ->setText('IPLIST_IPADDRESS')
                                        ->setIcon('network-wired')
                                        ->setDisplaySizesDefault(); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row fpcm-ui-padding-md-tb">
                            <div class="col-12 fpcm-ui-padding-none-lr">
                                <div class="row">
                                    <label class="col-12 col-sm-6 col-md-3 fpcm-ui-field-label-general">
                                        <?php $theView->icon('lock'); ?>
                                        <?php $theView->write('IPLIST_BLOCKTYPE'); ?>:
                                    </label>
                                    <div class="col-12 col-sm-6 col-md-9 fpcm-ui-padding-none-lr">
                                        <div class="fpcm-ui-controlgroup fpcm-ui-borderradius-remove-left">
                                            <?php $theView->checkbox('nocomments')->setText('IPLIST_NOCOMMENTS')->setSelected($object->getNocomments()); ?>
                                            <?php $theView->checkbox('nologin')->setText('IPLIST_NOLOGIN')->setSelected($object->getNologin()); ?>
                                            <?php $theView->checkbox('noaccess')->setText('IPLIST_NOACCESS')->setSelected($object->getNoaccess()); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>