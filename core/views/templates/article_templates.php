<table class="fpcm-ui-table fpcm-ui-articletemplates">
    <tr>
        <th class="fpcm-ui-editbutton-col"></th>
        <th><?php $FPCM_LANG->write('FILE_LIST_FILENAME'); ?></th>
        <th><?php $FPCM_LANG->write('FILE_LIST_FILESIZE'); ?></th>
        <th class="fpcm-th-select-row"></th>
    </tr>
    <?php fpcm\model\view\helper::notFoundContainer($templateFiles, 4); ?>

    <tr class="fpcm-td-spacer"><td></td></tr>
    <?php foreach ($templateFiles as $templateFile) : ?>
    <tr>
        <td class="fpcm-ui-editbutton-col fpcm-ui-center">
            <?php \fpcm\model\view\helper::linkButton($templateFile->getFileUrl(), 'GLOBAL_DOWNLOAD', '', 'fpcm-ui-button-blank fpcm-download-btn', '_blank'); ?>
            <?php fpcm\model\view\helper::editButton($templateFile->getEditUrl(), true, 'fpcm-articletemplates-edit'); ?>
        </td>
        <td><?php print $templateFile->getFilename(); ?></td>
        <td><?php print \fpcm\classes\tools::calcSize($templateFile->getFilesize()); ?></td>
        <td class="fpcm-td-select-row"><?php fpcm\model\view\helper::checkbox('deltplfiles[]', 'fpcm-list-selectbox', base64_encode($templateFile->getFilename()), '', '', false); ?></td>
    </tr>
    <?php endforeach; ?>

    <tr class="fpcm-td-spacer"><td colspan="3"></td></tr>
</table>

<p><?php print $maxFilesInfo; ?></p>

<table id="fpcm-ui-phpupload-filelist" class="fpcm-ui-table fpcm-ui-phpupload"></table>

<div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> fpcm-filemanager-buttons" id="article_template_buttons">
    <div class="fpcm-ui-margin-center">
        <?php fpcm\model\view\helper::linkButton('#', 'FILE_FORM_FILEADD', 'btnAddFile') ?>
        <?php fpcm\model\view\helper::submitButton('uploadFile', 'FILE_FORM_UPLOADSTART', 'start-upload fpcm-loader'); ?>
        <button type="reset" class="cancel-upload" id="btnCancelUpload"><?php $FPCM_LANG->write('FILE_FORM_UPLOADCANCEL'); ?></button>
        <input type="file" name="files[]" class="fpcm-ui-fileinput-select fpcm-hidden">
        <?php fpcm\model\view\helper::deleteButton('fileDelete'); ?>
    </div>
</div>