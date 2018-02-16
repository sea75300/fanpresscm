<p><?php print $maxFilesInfo; ?></p>

<div class="fpcm-ui-controlgroup fpcm-ui-marginbottom-lg" id="article_template_buttons">    
    <?php $theView->button('addFile')->setText('FILE_FORM_FILEADD')->setIcon('plus'); ?>
    <?php $theView->submitButton('uploadFile')->setText('FILE_FORM_UPLOADSTART')->setIcon('cloud-upload'); ?>
    <?php $theView->resetButton('cancelUpload')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban'); ?>
    <input type="file" name="files[]" class="fpcm-ui-fileinput-select fpcm-ui-hidden">
    <?php fpcm\view\helper::deleteButton('fileDelete'); ?>
</div>

<table id="fpcm-ui-phpupload-filelist" class="fpcm-ui-table fpcm-ui-marginbottom-lg fpcm-ui-filelist fpcm-ui-phpupload"></table>

<table class="fpcm-ui-table fpcm-ui-articletemplates">
    <tr>
        <th class="fpcm-ui-editbutton-col"></th>
        <th><?php $theView->write('FILE_LIST_FILENAME'); ?></th>
        <th><?php $theView->write('FILE_LIST_FILESIZE'); ?></th>
        <th class="fpcm-th-select-row"></th>
    </tr>
    <?php fpcm\view\helper::notFoundContainer($templateFiles, 4); ?>

    <tr class="fpcm-td-spacer"><td></td></tr>
    <?php foreach ($templateFiles as $templateFile) : ?>
    <tr>
        <td class="fpcm-ui-editbutton-col fpcm-ui-center">
            <?php $theView->linkButton(uniqid())->setText('GLOBAL_DOWNLOAD')->setUrl($templateFile->getFileUrl())->setIcon('cloud-download')->setIconOnly(true)->setTarget('_blank'); ?>
            <?php $theView->editButton(uniqid())->setUrlbyObject($templateFile)->setClass('fpcm-articletemplates-edit'); ?>
        </td>
        <td><?php print $templateFile->getFilename(); ?></td>
        <td><?php print \fpcm\classes\tools::calcSize($templateFile->getFilesize()); ?></td>
        <td class="fpcm-td-select-row"><?php fpcm\view\helper::checkbox('deltplfiles[]', 'fpcm-list-selectbox', base64_encode($templateFile->getFilename()), '', '', false); ?></td>
    </tr>
    <?php endforeach; ?>

    <tr class="fpcm-td-spacer"><td colspan="3"></td></tr>
</table>