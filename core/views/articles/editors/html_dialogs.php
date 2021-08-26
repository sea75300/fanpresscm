<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php include_once $theView->getIncludePath('common/html_editor_dialogs.php'); ?>

<!-- Vorlage einfügen -->
<div class="fpcm ui-hidden" id="fpcm-dialog-editor-html-insertdraft">
    <div class="row">
        <div class="col-sm-6 pe-4 py-2"><?php $theView->select('tpldraft')->setOptions($editorTemplatesList); ?></div>
        <div class="col-sm-12 py-2">
            <pre id="fpcm-dialog-editor-html-insertdraft-preview" class="CodeMirror cm-s-fpcm CodeMirror-wrap"></pre>
        </div>
    </div>
</div>