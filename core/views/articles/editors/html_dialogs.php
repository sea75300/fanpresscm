<?php /* @var $theView fpcm\view\viewVars */ ?>
<!-- Link einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertlink">
    <div class="row">
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_LINKURL'); ?></div>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb"><?php $theView->textInput('links[url]', 'linksurl')->setValue('http://'); ?></div>
        
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_LINKTXT'); ?></div>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb"><?php $theView->textInput('links[text]', 'linkstext'); ?></div>
        
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_LINKTXT'); ?></div>
        <div class="col-6 fpcm-ui-padding-md-tb"><?php $theView->select('links[target]', 'linkstarget')->setOptions($targets); ?></div>
        
        <?php if (count($cssClasses)) : ?>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_CSS_CLASS'); ?></div>
        <div class="col-6 fpcm-ui-padding-md-tb"><?php $theView->select('links[css]', 'linkscss')->setOptions($cssClasses); ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Bild einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertimage">
    <div class="row">
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_IMGPATH'); ?></div>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb"><?php $theView->textInput('images[path]', 'imagespath')->setValue('http://'); ?></div>
        
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_IMGALTTXT'); ?></div>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb"><?php $theView->textInput('images[alt]', 'imagesalt'); ?></div>
        
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_IMGALIGN'); ?></div>
        <div class="col-6 fpcm-ui-padding-md-tb"><?php $theView->select('images[align]', 'imagesalign')->setOptions($aligns); ?></div>
        
        <?php if (count($cssClasses)) : ?>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_CSS_CLASS'); ?></div>
        <div class="col-6 fpcm-ui-padding-md-tb"><?php $theView->select('images[css]', 'imagescss')->setOptions($cssClasses); ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Tabelle einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-inserttable">
    <div class="row">
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_INSERTTABLE_ROWS'); ?></div>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb"><?php $theView->textInput('table[rows]', 'tablerows')->setValue(1)->setMaxlenght(5)->setWrapper(false); ?></div>
        
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_INSERTTABLE_COLS'); ?></div>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb"><?php $theView->textInput('table[cols]', 'tablecols')->setValue(1)->setMaxlenght(5)->setWrapper(false); ?></div>
    </div>
</div>

<!-- Liste einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertlist">
    <div class="row">
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_INSERTTABLE_ROWS'); ?></div>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb"><?php $theView->textInput('list[rows]', 'listrows')->setValue(1)->setMaxlenght(5)->setWrapper(false); ?></div>
    </div>
</div>

<!-- Player einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertmedia">
    <div class="row">
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb align-self-center"><?php $theView->write('EDITOR_IMGPATH'); ?></div>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb"><?php $theView->textInput('media[path]', 'mediapath'); ?></div>
        
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb">
            <?php $theView->radiobutton('mediatype', 'mediatypea')->setText('EDITOR_INSERTMEDIA_AUDIO')->setClass('fpcm-editor-mediatype')->setValue('audio')->setSelected(true); ?>
        </div>
        <div class="col-sm-12 col-md-6 fpcm-ui-padding-md-tb">
            <?php $theView->radiobutton('mediatype', 'mediatypev')->setText('EDITOR_INSERTMEDIA_VIDEO')->setClass('fpcm-editor-mediatype')->setValue('video'); ?>
        </div>
    </div>
</div>

<!-- Tabelle einfügen -->
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertcolor">
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-sm-12 col-md-6 align-self-center"><?php $theView->write('EDITOR_INSERTCOLOR_HEXCODE'); ?></div>
        <div class="col-sm-12 col-md-6 "><?php $theView->textInput('colorhexcode')->setMaxlenght(5); ?></div>
    </div>

    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-12 fpcm-dialog-editor-colors fpcm-ui-center fpcm-ui-editor-metabox"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="align-self-center col-12 col-md-6 fpcm-ui-center">
            <?php $theView->radiobutton('color_mode', 'color_mode1')->setText('EDITOR_INSERTCOLOR_TEXT')->setClass('fpcm-ui-editor-colormode')->setValue('color')->setSelected(true); ?>
        </div>
        <div class="align-self-center col-12 col-md-6 fpcm-ui-center">
            <?php $theView->radiobutton('color_mode', 'color_mode2')->setText('EDITOR_INSERTCOLOR_BACKGROUND')->setClass('fpcm-ui-editor-colormode')->setValue('background'); ?>
        </div>
    </div>
</div>

<?php $count = 1; ?>
<!-- Smiley einfügen -->
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertsmileys"></div>

<!-- Symbol einfügen -->
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertsymbol">
    <div class="row no-gutters">
        <?php for($i=161;$i<=450;$i++) : ?>        
        <div class="col-1"><a class="fpcm-editor-htmlsymbol" data-symbolcode="&#<?php print $i; ?>;" href="#">&#<?php print $i; ?>;</a></div>
        <?php endfor; ?>
    </div>
</div>

<!-- Vorlage einfügen -->
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertdraft">
    <div class="row">
        <div class="col-sm-6 fpcm-ui-padding-md-tb"><?php $theView->select('tpldraft')->setOptions($editorTemplatesList); ?></div>
        <div class="col-sm-12 fpcm-ui-padding-md-tb">
            <pre id="fpcm-dialog-editor-html-insertdraft-preview" class="CodeMirror cm-s-fpcm CodeMirror-wrap"></pre>
        </div>
    </div>
</div>