<div class="fpcm-content-wrapper">
    <div class="fpcm-tabs-general">
        <ul>
            <li><a href="#tabs-ip"><?php $theView->write('IPLIST_ADDIP'); ?></a></li>
        </ul>            

        <div id="tabs-ip">          
            
            <div class="row fpcm-ui-padding-md-tb fpcm-ui-editor-metabox">
                <div class="col-12">
                    <?php $theView->write('IPLIST_DESCRIPTION'); ?>
                </div>
            </div>
            
            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
                    <?php $theView->write('IPLIST_IPADDRESS'); ?>
                </div>
                <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
                    <?php $theView->textInput('ipaddress'); ?>
                </div>
            </div>
            
            <div class="row fpcm-ui-padding-md-tb">
                <div class="col-sm-12 col-md-3 fpcm-ui-padding-none-lr">
                    <?php $theView->write('IPLIST_BLOCKTYPE'); ?>
                </div>
                <div class="col-sm-12 col-md-9 fpcm-ui-padding-none-lr">
                    <div class="fpcm-ui-controlgroup">
                        <?php $theView->checkbox('nocomments')->setText('IPLIST_NOCOMMENTS')->setClass('fpcm-ui-ipadresses-rolls')->setIcon('comment-slash'); ?>
                        <?php $theView->checkbox('nologin')->setText('IPLIST_NOLOGIN')->setClass('fpcm-ui-ipadresses-rolls')->setIcon('sign-in-alt'); ?>
                        <?php $theView->checkbox('noaccess')->setText('IPLIST_NOACCESS')->setClass('fpcm-ui-ipadresses-rolls')->setIcon('ban'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>