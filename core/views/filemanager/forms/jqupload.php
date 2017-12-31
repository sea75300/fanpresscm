<link rel="stylesheet" type="text/css" href="<?php print $jquploadPath ?>css/jquery.fileupload.css">
<link rel="stylesheet" type="text/css" href="<?php print $jquploadPath ?>css/jquery.fileupload-ui.css">
<noscript><link rel="stylesheet" type="text/css" href="<?php print $jquploadPath ?>css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" type="text/css" href="<?php print $jquploadPath ?>css/jquery.fileupload-ui-noscript.css"></noscript>

<form id="fileupload" action="<?php print $actionPath; ?>" method="POST" enctype="multipart/form-data">

    <div class="fileupload-buttonbar">
        <div class="fileupload-progress fade fpcm-hidden">
            <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>

    <div id="fpcm-filemanager-upload-drop"><h4 class="fpcm-ui-center"><?php $FPCM_LANG->write('FILE_LIST_UPLOADDROP'); ?></h4></div>
    
    <table role="presentation" class="fpcm-ui-table fpcm-ui-uploadlist">
        <tbody class="files"></tbody>
    </table>
    
    <div class="<?php \fpcm\model\view\helper::buttonsContainerClass(); ?> <?php if (!$styleLeftMargin) : ?>fpcm-buttons-fixed-full<?php endif; ?> fileupload-buttonbar">
        <div class="fileupload-buttons">
            <span class="fileinput-button">
                <span><?php $FPCM_LANG->write('FILE_FORM_FILEADD'); ?></span>
                <input type="file" name="files[]" multiple>
            </span>
            <button type="submit" class="start"><?php $FPCM_LANG->write('FILE_FORM_UPLOADSTART'); ?></button>
            <button type="reset" class="cancel"><?php $FPCM_LANG->write('FILE_FORM_UPLOADCANCEL'); ?></button>
            <span class="fileupload-process"></span>
        </div>
    </div>
</form>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="name">{%=file.name%}</span>
            <strong class="error"></strong>
        </td>

        <td class="jqupload-row-buttons">
            {% if (!i && !o.options.autoUpload) { %}
                <button class="start jqupload-btn"><?php $FPCM_LANG->write('FILE_FORM_UPLOADSTART'); ?></button>
            {% } %}
            {% if (!i) { %}
                <button class="cancel jqupload-btn"><?php $FPCM_LANG->write('FILE_FORM_UPLOADCANCEL'); ?></button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>

<script id="template-download" type="text/x-tmpl">
</script>
<script src="<?php print $jquploadPath ?>js/template.js"></script>
<script src="<?php print $jquploadPath ?>js/jquery.iframe-transport.js"></script>
<script src="<?php print $jquploadPath ?>js/jquery.fileupload.js"></script>
<script src="<?php print $jquploadPath ?>js/jquery.fileupload-process.js"></script>
<script src="<?php print $jquploadPath ?>js/jquery.fileupload-validate.js"></script>
<script src="<?php print $jquploadPath ?>js/jquery.fileupload-ui.js"></script>
<script src="<?php print $jquploadPath ?>js/jquery.fileupload-jquery-ui.js"></script>
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="<?php print $jquploadPath ?>js/cors/jquery.xdr-transport.js"></script>
<![endif]-->