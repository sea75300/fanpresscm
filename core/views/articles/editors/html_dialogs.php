<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php include_once $theView->getIncludePath('common/html_editor_dialogs.php'); ?>

<!-- Vorlage einfügen -->
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertdraft">
    <div class="row">
        <div class="col-sm-6 fpcm-ui-padding-md-tb"><?php $theView->select('tpldraft')->setOptions($editorTemplatesList); ?></div>
        <div class="col-sm-12 fpcm-ui-padding-md-tb">
            <pre id="fpcm-dialog-editor-html-insertdraft-preview" class="CodeMirror cm-s-fpcm CodeMirror-wrap"></pre>
        </div>
    </div>
</div>