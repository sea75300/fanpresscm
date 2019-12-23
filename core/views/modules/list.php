<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="fpcm-content-wrapper">
    <div class="fpcm-ui-tabs-general" id="fpcm-tabs-modules">
        <ul>
            <li data-dataview-list="modulesLocal"><a href="<?php print $theView->controllerLink('ajax/modules/fetch', ['mode' => 'local']); ?>"><?php $theView->write('MODULES_LIST_HEADLINE'); ?></a></li>
            <li data-dataview-list="modulesRemote"><a href="<?php print $theView->controllerLink('ajax/modules/fetch', ['mode' => 'remote']); ?>"><?php $theView->write('MODULES_LIST_AVAILABLE'); ?></a></li>
            <?php if ($theView->permissions->modules->install) : ?><li><a href="#tabs-modules-upload"><?php $theView->write('MODULES_LIST_UPLOAD'); ?></a></li><?php endif; ?>
        </ul>

        <?php if ($theView->permissions->modules->install) : ?>
        <div id="tabs-modules-upload">
            
            <fieldset>
                <legend><?php $theView->write('MODULES_LIST_UPLOAD'); ?>: <?php print $maxFilesInfo; ?></legend>
                        
                <div class="fpcm-ui-controlgroup fpcm-ui-padding-md-tb" id="article_template_buttons">    
                    <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus'); ?>
                    <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('upload'); ?>
                    <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban'); ?>
                    <input type="file" name="files[]" class="fpcm-ui-fileinput-select fpcm-ui-hidden">
                </div>
            </fieldset>
            
        </div>
        <?php endif; ?>
    </div>
</div>