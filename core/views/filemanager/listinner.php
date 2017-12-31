<?php if (!count($files)) : ?>
<table class="fpcm-ui-table fpcm-ui-nobg">
    <?php \fpcm\model\view\helper::notFoundContainer($files, 4); ?>
</table>
<?php endif; ?>
<div class="fpcm-filelist-images">
    <?php foreach($files AS $file) : ?>
    <div class="fpcm-filelist-thumb-box">
        <div class="fpcm-filelist-thumb-box-inner">
            <div class="fpcm-ui-center">
                <a href="<?php print $file->getImageUrl(); ?>" target="_blank" class="fpcm-link-fancybox" data-fancybox="group" >
                    <img src="<?php if (file_exists($file->getFileManagerThumbnail())) : ?><?php print $file->getFileManagerThumbnailUrl(); ?><?php else : ?><?php print $FPCM_THEMEPATH; ?>dummy.png<?php endif; ?>" width="100" height="100" title="<?php print $file->getFileName(); ?>">
                </a>
            </div>

            <div class="fpcm-filelist-actions-box fpcm-ui-center">
                <div class="fpcm-filelist-actions">
                    <a href="<?php print $file->getThumbnailUrl(); ?>" class="fpcm-ui-button fpcm-ui-button-blank fpcm-filelist-link-thumb" target="_blank" title="<?php $FPCM_LANG->write('FILE_LIST_OPEN_THUMB'); ?>"><?php $FPCM_LANG->write('FILE_LIST_OPEN_THUMB'); ?></a>
                    <a href="<?php print $file->getImageUrl(); ?>" target="_blank" class="fpcm-ui-button fpcm-ui-button-blank fpcm-filelist-link-full fpcm-file-list-link" title="<?php $FPCM_LANG->write('FILE_LIST_OPEN_FULL'); ?>"><?php $FPCM_LANG->write('FILE_LIST_OPEN_FULL'); ?></a>
                    <?php if ($mode == 2) : ?>
                    <a href="<?php print $file->getThumbnailUrl(); ?>" imgtxt="<?php print $file->getFilename(); ?>" class="fpcm-ui-button fpcm-ui-button-blank fpcm-filelist-tinymce-thumb" title="<?php $FPCM_LANG->write('FILE_LIST_INSERT_THUMB'); ?>"><?php $FPCM_LANG->write('FILE_LIST_INSERT_THUMB'); ?></a>
                    <a href="<?php print $file->getImageUrl(); ?>" imgtxt="<?php print $file->getFilename(); ?>" class="fpcm-ui-button fpcm-ui-button-blank fpcm-filelist-tinymce-full" title="<?php $FPCM_LANG->write('FILE_LIST_INSERT_FULL'); ?>"><?php $FPCM_LANG->write('FILE_LIST_INSERT_FULL'); ?></a>
                    <?php endif; ?>
                    <?php if ($mode == 3) : ?>
                    <a href="<?php print $file->getImageUrl(); ?>" imgtxt="<?php print $file->getFilename(); ?>" class="fpcm-ui-button fpcm-ui-button-blank fpcm-filelist-articleimage" title="<?php $FPCM_LANG->write('EDITOR_ARTICLEIMAGE'); ?>"><?php $FPCM_LANG->write('EDITOR_ARTICLEIMAGE'); ?></a>
                    <?php endif; ?>                    
                </div>
                
                <div class="fpcm-filelist-actions-checkbox">
                    <?php fpcm\model\view\helper::checkbox('filenames[]', 'fpcm-list-selectbox', base64_encode($file->getFilename()), '', 'cb_'.$file->getFilename(), false); ?>
                </div>
                
                <div class="fpcm-clear"></div>
            </div> 
            
            <div class="fpcm-filelist-meta fpcm-ui-left fpcm-small-text">
                <table class="fpcm-ui-table fpcm-ui-nobg">
                    <tr>
                        <td><strong><?php $FPCM_LANG->write('FILE_LIST_UPLOAD_DATE'); ?>:</strong></td>
                        <td><?php \fpcm\model\view\helper::dateText($file->getFiletime()); ?></td>                    
                    </tr>
                    <tr>
                        <td><strong><?php $FPCM_LANG->write('FILE_LIST_UPLOAD_BY'); ?>:</strong></td>
                        <td><?php print isset($users[$file->getUserid()]) ? $users[$file->getUserid()]->getDisplayName() : $FPCM_LANG->translate('GLOBAL_NOTFOUND'); ?></td>                    
                    </tr>
                    <tr>
                        <td><strong><?php $FPCM_LANG->write('FILE_LIST_FILESIZE'); ?>:</strong></td>
                        <td><?php print \fpcm\classes\tools::calcSize($file->getFilesize()); ?></td>                    
                    </tr>
                    <tr>
                        <td><strong><?php $FPCM_LANG->write('FILE_LIST_RESOLUTION'); ?>:</strong></td>
                        <td><?php print $file->getWidth(); ?> <span class="fa fa-times fa-fw"></span> <?php print $file->getHeight(); ?> <?php $FPCM_LANG->write('FILE_LIST_RESOLUTION_PIXEL'); ?></td>                    
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
    <div class="fpcm-clear"></div>
</div>

<span id="fpcm-filelist-images-finished"></span>

<?php include dirname(__DIR__).'/components/pager.php'; ?>