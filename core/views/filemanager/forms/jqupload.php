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

    <div class="row no-gutters">
        
        <div class="col-12 col-xl-6 pr-0 pr-xl-1">
            <fieldset class="fpcm-ui-margin-lg-bottom">
                <legend><?php $theView->write('FILE_LIST_UPLOADFORM'); ?></legend>

                <div class="fpcm-ui-padding-md-tb fileupload-buttonbar">
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
            </fieldset>
        </div>

        <div class="col-12 col-xl-6 pl-0 pl-xl-1">
            <div class="row no-gutters align-self-center justify-content-center">        
                <div id="fpcm-filemanager-upload-drop" class="col-12 fpcm-ui-background-white-100">
                    <h4 class="fpcm-ui-center"><?php $theView->icon('file-upload')->setSize('4x')->setClass('fpcm-ui-padding-md-bottom fpcm-ui-status-075'); ?><br><?php $theView->write('FILE_LIST_UPLOADDROP'); ?></h4>
                </div>
            </div>
        </div>
        
    </div>


    <div role="presentation" class="fpcm-ui-margin-lg-top">
        <div class="files"></div>
    </div>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">

{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="row template-upload fade fpcm-ui-padding-md-tb fpcm-ui-background-white-50p fpcm-ui-border-radius-all fpcm-ui-margin-md-top fpcm-ui-margin-md-bottom">
        <div class="col-12 col-sm-auto fpcm-ui-center jqupload-row-buttons">
        
            {% if (!i && !o.options.autoUpload) { %}
                <?php $theView->button('startlist')->setClass('start')->setText('FILE_FORM_UPLOADSTART')->setIcon('upload')->setIconOnly(true); ?>
            {% } %}
            {% if (!i) { %}
                <?php $theView->button('cancellist')->setClass('cancel')->setText('FILE_FORM_UPLOADCANCEL')->setIcon('ban')->setIconOnly(true); ?>
            {% } %}
        </div>

        <div class="col-12 col-sm-auto align-self-center fpcm-ui-ellipsis pt-3 pt-sm-0">
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