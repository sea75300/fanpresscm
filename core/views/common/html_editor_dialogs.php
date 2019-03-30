<?php /* @var $theView fpcm\view\viewVars */ ?>
<!-- Link einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertlink">
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('links[url]', 'linksurl')
                ->setValue('http://')
                ->setWrapper(false)
                ->setText('EDITOR_LINKURL')
                ->setIcon('external-link-alt')
                ->setSize('lg')
                ->setClass('col-6 col-md-8 fpcm-ui-border-blue-light fpcm-ui-border-radius-right fpcm-ui-input-wrapper-inner fpcm-ui-element-min-height-md')
                ->setLabelClass('col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div>
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('links[text]', 'linkstext')
                ->setWrapper(false)
                ->setText('EDITOR_LINKTXT')
                ->setIcon('keyboard')
                ->setSize('lg')
                ->setClass('col-6 col-md-8 fpcm-ui-border-blue-light fpcm-ui-border-radius-right fpcm-ui-input-wrapper-inner fpcm-ui-element-min-height-md')
                ->setLabelClass('col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div>   
    <div class="row fpcm-ui-padding-md-tb">            
        <label class="col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md">
            <?php $theView->icon('window-restore')->setSize('lg'); ?>
            <?php $theView->write('EDITOR_LINKTARGET'); ?>
        </label>
        <div class="col-8 fpcm-ui-padding-none-lr"><?php $theView->select('links[target]', 'linkstarget')->setOptions($targets); ?></div>
    </div>
    <?php if (count($cssClasses)) : ?>
    <div class="row fpcm-ui-padding-md-tb">            
        <label class="col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md">
            <?php $theView->icon('paint-roller')->setSize('lg'); ?>
            <?php $theView->write('EDITOR_CSS_CLASS'); ?>
        </label>
        <div class="col-8 fpcm-ui-padding-none-lr"><?php $theView->select('links[css]', 'linkscss')->setOptions($cssClasses); ?></div>
    </div>
    <?php endif; ?>
</div>

<!-- Bild einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertimage">
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('images[path]', 'imagespath')
                ->setValue('http://')
                ->setWrapper(false)
                ->setText('EDITOR_IMGPATH')
                ->setIcon('image')
                ->setSize('lg')
                ->setClass('col-6 col-md-8 fpcm-ui-border-blue-light fpcm-ui-border-radius-right fpcm-ui-input-wrapper-inner fpcm-ui-element-min-height-md')
                ->setLabelClass('col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div>
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('images[alt]', 'imagesalt')
                ->setWrapper(false)
                ->setText('EDITOR_IMGALTTXT')
                ->setIcon('keyboard')
                ->setSize('lg')
                ->setClass('col-6 col-md-8 fpcm-ui-border-blue-light fpcm-ui-border-radius-right fpcm-ui-input-wrapper-inner fpcm-ui-element-min-height-md')
                ->setLabelClass('col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div>   
    <div class="row fpcm-ui-padding-md-tb">            
        <label class="col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md">
            <?php $theView->icon('align-center')->setSize('lg'); ?>
            <?php $theView->write('EDITOR_IMGALIGN'); ?>
        </label>
        <div class="col-8 fpcm-ui-padding-none-lr"><?php $theView->select('images[align]', 'imagesalign')->setOptions($aligns); ?></div>
    </div>
    <?php if (count($cssClasses)) : ?>
    <div class="row fpcm-ui-padding-md-tb">            
        <label class="col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md">
            <?php $theView->icon('paint-roller')->setSize('lg'); ?>
            <?php $theView->write('EDITOR_CSS_CLASS'); ?>
        </label>
        <div class="col-8 fpcm-ui-padding-none-lr"><?php $theView->select('images[css]', 'imagescss')->setOptions($cssClasses); ?></div>
    </div>
    <?php endif; ?>
</div>

<!-- Tabelle einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-inserttable">
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('table[rows]', 'tablerows')
                ->setValue(1)->setMaxlenght(5)
                ->setWrapper(false)->setText('EDITOR_INSERTTABLE_ROWS')
                ->setIcon('arrow-down')
                ->setSize('lg')
                ->setLabelClass('col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div>
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('table[rows]', 'tablecols')
                ->setValue(1)->setMaxlenght(5)
                ->setWrapper(false)->setText('EDITOR_INSERTTABLE_COLS')
                ->setIcon('arrow-right')
                ->setSize('lg')
                ->setLabelClass('col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div>
</div>

<!-- Liste einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertlist">
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('list[rows]', 'listrows')
                ->setValue(1)->setMaxlenght(5)
                ->setWrapper(false)->setText('EDITOR_INSERTTABLE_ROWS')
                ->setIcon('keyboard')
                ->setSize('lg')
                ->setLabelClass('col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div>
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('list[type]', 'listtype')
                ->setValue('')
                ->setWrapper(false)->setText('EDITOR_INSERTLIST_TYPESIGN')
                ->setIcon('list-ul')
                ->setSize('lg')
                ->setClass('col-6 col-md-4 fpcm-ui-border-blue-light fpcm-ui-border-radius-right fpcm-ui-input-wrapper-inner fpcm-ui-element-min-height-md')
                ->setLabelClass('col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div>
</div>

<!-- Player einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertmedia">
    
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('media[path]', 'mediapath')
                ->setValue('http://')
                ->setWrapper(false)
                ->setText('EDITOR_IMGPATH')
                ->setIcon('film ')
                ->setSize('lg')
                ->setClass('col-6 col-md-8 fpcm-ui-border-blue-light fpcm-ui-border-radius-right fpcm-ui-input-wrapper-inner fpcm-ui-element-min-height-md')
                ->setLabelClass('col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div> 
    
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('media[path2]', 'mediapath2')
                ->setValue('')
                ->setWrapper(false)
                ->setText('EDITOR_IMGPATH_ALT')
                ->setIcon('file-video')
                ->setSize('lg')
                ->setClass('col-6 col-md-8 fpcm-ui-border-blue-light fpcm-ui-border-radius-right fpcm-ui-input-wrapper-inner fpcm-ui-element-min-height-md')
                ->setLabelClass('col-6 col-md-4 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div> 
    
    <div class="row fpcm-ui-padding-md-tb">        
        <div class="col-12 fpcm-ui-center">
            <div id="fpcm-ui-editor-media-controlgroup">
                <?php $theView->radiobutton('mediatype', 'mediatypea')->setText('EDITOR_INSERTMEDIA_AUDIO')->setClass('fpcm-editor-mediatype')->setValue('audio')->setSelected(true); ?>
                <?php $theView->radiobutton('mediatype', 'mediatypev')->setText('EDITOR_INSERTMEDIA_VIDEO')->setClass('fpcm-editor-mediatype')->setValue('video'); ?>
            </div>
        </div>
    </div>
</div>

<!-- Farben einfügen -->
<div class="fpcm-ui-dialog-layer fpcm-ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertcolor">
    
    <div class="row fpcm-ui-padding-md-tb">
        <?php $theView->textInput('colorhexcode')
                ->setValue('#000000')
                ->setWrapper(false)
                ->setMaxlenght(7)
                ->setText('EDITOR_INSERTCOLOR_HEXCODE')
                ->setIcon('eye-dropper')
                ->setSize('lg')
                ->setClass('col-6 col-md-4 fpcm-ui-border-blue-light fpcm-ui-border-radius-right fpcm-ui-input-wrapper-inner fpcm-ui-element-min-height-md')
                ->setLabelClass('col-6 col-md-8 fpcm-ui-padding-none-lr fpcm-ui-border-blue-light fpcm-ui-border-none-right fpcm-ui-label-bg-grey fpcm-ui-input-wrapper-inner fpcm-ui-border-radius-left fpcm-ui-element-min-height-md'); ?>
    </div>    

    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-12 fpcm-dialog-editor-colors fpcm-ui-center fpcm-ui-editor-metabox"></div>
    </div>
    
    <div class="row no-gutters fpcm-ui-padding-md-tb">
        <div class="col-12 fpcm-ui-padding-md-tb fpcm-ui-center">
            <div id="fpcm-ui-editor-color-controlgroup">
                <?php $theView->radiobutton('color_mode', 'color_mode1')->setText('EDITOR_INSERTCOLOR_TEXT')->setClass('fpcm-ui-editor-colormode')->setValue('color')->setSelected(true); ?>
                <?php $theView->radiobutton('color_mode', 'color_mode2')->setText('EDITOR_INSERTCOLOR_BACKGROUND')->setClass('fpcm-ui-editor-colormode')->setValue('background'); ?>
            </div>
        </div>
    </div>
</div>

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