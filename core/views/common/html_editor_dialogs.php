<?php /* @var $theView fpcm\view\viewVars */ ?>
<!-- Link einfügen -->  
<div class="d-none" id="fpcm-dialog-editor-html-insertlink">
    <div class="row">
        <?php $theView->textInput('links[url]')
                ->setType('url')
                ->setValue('')
                ->setText('EDITOR_LINKURL')
                ->setIcon('external-link-alt')
                ->setSize('lg') ?>
    </div>
    <div class="row">
        <?php $theView->textInput('links[text]')
                ->setText('EDITOR_LINKTXT')
                ->setIcon('keyboard')
                ->setSize('lg'); ?>
    </div>   
    <div class="row">            
        <?php $theView->select('links[target]')->setOptions($targets)->setText('EDITOR_LINKTARGET')->setIcon('window-restore')->setSize('lg'); ?>
    </div>
    <?php if (count($cssClasses)) : ?>
    <div class="row">            
        <?php $theView->select('links[css]')->setOptions($cssClasses)->setText('EDITOR_CSS_CLASS')->setIcon('paint-roller')->setSize('lg'); ?>
    </div>
    <?php endif; ?>
    <div class="row">            
        <?php $theView->textInput('links[rel]')->setText('EDITOR_LINKREL')->setIcon('cog')->setSize('lg'); ?>
    </div>
</div>

<!-- Bild einfügen -->  
<div class="d-none" id="fpcm-dialog-editor-html-insertimage">
    <div class="row">
        <?php $theView->textInput('images[path]', 'imagespath')
                ->setType('url')
                ->setValue('')
                ->setText('EDITOR_IMGPATH')
                ->setIcon('image')
                ->setSize('lg'); ?>
    </div>
    <div class="row">
        <?php $theView->textInput('images[alt]', 'imagesalt')
                ->setText('EDITOR_IMGALTTXT')
                ->setIcon('keyboard')
                ->setSize('lg'); ?>
    </div>   
    <div class="row">            
        <?php $theView->select('images[align]', 'imagesalign')->setOptions($aligns)->setText('EDITOR_IMGALIGN')->setIcon('align-center')->setSize('lg'); ?>
    </div>
    <?php if (count($cssClasses)) : ?>
    <div class="row">            
        <?php $theView->select('images[css]', 'imagescss')->setOptions($cssClasses)->setText('EDITOR_CSS_CLASS')->setIcon('paint-roller')->setSize('lg'); ?>
    </div>
    <?php endif; ?>
</div>

<!-- Player einfügen -->  
<div class="d-none" id="fpcm-dialog-editor-html-insertmedia">
    
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="row g-0">
                <?php $theView->textInput('media[path]', 'mediapath')
                        ->setType('url')
                        ->setValue('')
                        ->setText('EDITOR_IMGPATH')
                        ->setIcon('film ')
                        ->setSize('lg'); ?>
            </div>
        </div>
        <div class="col text-center mb-3 align-self-center">
            <?php $theView->icon('photo-video')->setText('EDITOR_INSERTMEDIA_FORMAT_SELECT')->setIconOnly(); ?>
        </div>
        <div class="col-11 col-md-3">
            <?php $theView->select('media[format]', 'mediaformat')->setOptions($playerFormats)->setClass('fpcm-editor-mediaformat')->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div> 
    
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="row g-0">
                <?php $theView->textInput('media[path]', 'mediapath2')
                        ->setType('url')
                        ->setValue('')
                        ->setText('EDITOR_IMGPATH_ALT')
                        ->setIcon('file-video')
                        ->setSize('lg'); ?>
            </div>
        </div>
        <div class="col text-center mb-3 align-self-center">
            <?php $theView->icon('photo-video')->setText('EDITOR_INSERTMEDIA_FORMAT_SELECT')->setIconOnly(); ?>
        </div>
        <div class="col-11 col-md-3">
            <?php $theView->select('media[format2]', 'mediaformat2')->setOptions($playerFormats)->setClass('fpcm-editor-mediaformat')->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div> 
    
    <div class="row">
        <div class="col-12 col-md-8 px-0">
            <div class="row">
                <?php $theView->textInput('media[poster]', 'mediaposter')
                        ->setType('url')
                        ->setValue('')
                        ->setText('EDITOR_INSERTMEDIA_POSTER')
                        ->setIcon('file-image')
                        ->setSize('lg'); ?>
            </div>
        </div>
        <div class="col mb-3 align-self-center">
            <?php $theView->button('insertposterimg', 'insertposterimg')->setText('HL_FILES_MNG')->setIcon('image')->setIconOnly(); ?>
        </div>
    </div> 
    
    <div class="row my-2 row-cols-1 row-cols-sm-4">
        <div class="col">
            <div class="d-flex justify-content-center">
                <?php $theView->radiobutton('mediatype', 'mediatypea')
                        ->setText('EDITOR_INSERTMEDIA_AUDIO')
                        ->setClass('fpcm-editor-mediatype')
                        ->setValue('audio')
                        ->setSelected(true)
                        ->setSwitch(true); ?>

            </div>
        </div>
        <div class="col">
            <div class="d-flex justify-content-center">
                <?php $theView->radiobutton('mediatype', 'mediatypev')
                        ->setText('EDITOR_INSERTMEDIA_VIDEO')
                        ->setClass('fpcm-editor-mediatype')
                        ->setValue('video')
                        ->setSwitch(true); ?>
            
            </div>
        </div>
        <div class="col">
            <div class="d-flex justify-content-center">
                <?php $theView->checkbox('controls', 'controls')
                        ->setText('EDITOR_INSERTMEDIA_CONTROLS')
                        ->setValue(1)
                        ->setSelected(true)
                        ->setSwitch(true); ?>

            </div>
        </div>
        <div class="col">
            <div class="d-flex justify-content-center">
                <?php $theView->checkbox('autoplay', 'autoplay')
                        ->setText('EDITOR_INSERTMEDIA_AUTOPLAY')
                        ->setValue(1)
                        ->setSwitch(true); ?>
            
            </div>
        </div>
    </div>      
    
    <div class="row g-0 align-self-center align-content-center justify-content-center" id="fpcm-dialog-editor-html-insertmedia-preview"></div>
</div>

<!-- Farben einfügen -->
<div class="d-none" id="fpcm-dialog-editor-html-insertcolor">
    
    <div class="row">
        <?php $theView->textInput('colorhexcode')
                ->setValue('#000000')
                ->setType('color')
                ->setText('EDITOR_INSERTCOLOR_HEXCODE')
                ->setIcon('eye-dropper')
                ->setSize('lg')
                ->setLabelSize([6])
                ->setClass('h-100'); ?>
    </div>    

    <div class="row">
        <div class="col-12 fpcm-dialog-editor-colors text-center"></div>
    </div>
    
    <div class="row my-2 row-cols-1 row-cols-sm-4">
        <div class="col">&nbsp;</div>
        <div class="col">
            <div class="d-flex justify-content-center">
                <?php $theView->radiobutton('color_mode', 'color_mode1')
                        ->setText('EDITOR_INSERTCOLOR_TEXT')
                        ->setClass('fpcm-ui-editor-colormode')
                        ->setValue('color')
                        ->setSelected(true)
                        ->setSwitch(true); ?>
                
            </div>
            
        </div>
        <div class="col">
            <div class="d-flex justify-content-center">
                <?php $theView->radiobutton('color_mode', 'color_mode2')
                        ->setText('EDITOR_INSERTCOLOR_BACKGROUND')
                        ->setClass('fpcm-ui-editor-colormode')
                        ->setValue('background')
                        ->setSwitch(true); ?>                
            </div>
        </div>
        <div class="col">&nbsp;</div>
    </div>
</div>

<!-- Vorlage einfügen -->
<div class="d-none" id="fpcm-dialog-editor-html-insertquote">
    <div class="row">
        <div class="col-12 mb-2">
            <?php $theView->textarea('quote[text]')->setPlaceholder(true)->setText('EDITOR_HTML_BUTTONS_QUOTE_TEXT')->setIcon('keyboard')->setClass('fpcm ui-textarea-medium ui-textarea-noresize'); ?>
        </div>
    </div>
    <div class="row">
        <?php $theView->textInput('quote[src]')
                ->setValue('')
                ->setText('TEMPLATE_ARTICLE_SOURCES')
                ->setIcon('external-link-alt')
                ->setSize('lg'); ?>
    </div>   
    
    <div class="row my-2 row-cols-1 row-cols-sm-4">
        <div class="col">&nbsp;</div>
        <div class="col">
            <div class="d-flex justify-content-center">
                <?php $theView->radiobutton('quote[type]', 'quotetype1')
                        ->setText('EDITOR_HTML_BUTTONS_QUOTE_BLOCK')
                        ->setClass('fpcm-ui-editor-quotemode')
                        ->setValue('blockquote')
                        ->setSelected(true)
                        ->setSwitch(true); ?>
            </div>
        </div>
        <div class="col">
            <div class="d-flex justify-content-center">
                <?php $theView->radiobutton('quote[type]', 'quotetype2')
                        ->setText('EDITOR_HTML_BUTTONS_QUOTE_INLINE')
                        ->setClass('fpcm-ui-editor-quotemode')
                        ->setValue('q')
                        ->setSwitch(true); ?>                
            </div>
        </div>
        <div class="col">&nbsp;</div>
    </div>    
</div>