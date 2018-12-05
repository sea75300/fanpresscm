<?php /* @var $theView fpcm\view\viewVars */ ?>
<link rel="stylesheet" type="text/css" href="<?php print $jquploadPath ?>css/jquery.fileupload.css">
<link rel="stylesheet" type="text/css" href="<?php print $jquploadPath ?>css/jquery.fileupload-ui.css">
<noscript><link rel="stylesheet" type="text/css" href="<?php print $jquploadPath ?>css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" type="text/css" href="<?php print $jquploadPath ?>css/jquery.fileupload-ui-noscript.css"></noscript>

<form id="fileupload" action="<?php print $actionPath; ?>" method="POST" enctype="multipart/form-data">

    <div class="fileupload-progress fpcm-ui-fade fpcm-ui-box-sizing-none fpcm-ui-margin-md-bottom fpcm-ui-hidden">
        <div class="progress active ui-progressbar ui-corner-all ui-widget ui-widget-content" role="progressbar" aria-valuemin="0" aria-valuemax="100">
            <div class="ui-progressbar-value ui-corner-left ui-widget-header progress-bar progress-bar-success" style="width:0%;"></div>
        </div>
        <div class="progress-extended">&nbsp;</div>        
    </div>

    <div class="fpcm-ui-margin-lg-bottom fileupload-buttonbar">
        <div class="fileupload-buttons fpcm-ui-controlgroup">
            <a class="fileinput-button">
                <?php $theView->icon('plus'); ?>
                <span><?php $theView->write('FILE_FORM_FILEADD'); ?></span>
                <input type="file" name="files[]" multiple>
            </a>
            
            <?php $theView->submitButton('start')->setText('FILE_FORM_UPLOADSTART')->setClass('start')->setIcon('upload'); ?>
            <?php $theView->resetButton('cancel')->setText('FILE_FORM_UPLOADCANCEL')->setClass('cancel')->setIcon('ban'); ?>
        </div>
    </div>

    <div id="fpcm-filemanager-upload-drop">
        <h4 class="fpcm-ui-center"><?php $theView->icon('images', 'far')->setSize('4x')->setClass('fpcm-ui-padding-md-bottom fpcm-ui-status-075'); ?><br><?php $theView->write('FILE_LIST_UPLOADDROP'); ?></h4>
    </div>

    <div role="presentation" class="fpcm-ui-margin-lg-top">
        <div class="files"></div>
    </div>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="row template-upload fade fpcm-ui-padding-md-tb">
        <div class="col-6 col-sm-4 col-md-2 fpcm-ui-center jqupload-row-buttons fpcm-ui-padding-none-lr">
        
            {% if (!i && !o.options.autoUpload) { %}
                <?php $theView->button('startlist')->setClass('start')->setText('FILE_FORM_UPLOADSTART')->setIcon('upload')->setIconOnly(true); ?>
            {% } %}
            {% if (!i) { %}
                <?php $theView->button('cancellist')->setClass('cancel')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban')->setIconOnly(true); ?>
            {% } %}
        </div>

        <div class="col-6 col-sm-8 col-md-10 align-self-center fpcm-ui-ellipsis">
            <span class="name">{%=file.name%}</span>
            <strong class="error"></strong>
        </div>
    </div>
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