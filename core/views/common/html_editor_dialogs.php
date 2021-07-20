<?php /* @var $theView fpcm\view\viewVars */ ?>
<!-- Link einfügen -->  
<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertlink">
    <div class="row py-2">
        <?php $theView->textInput('links[url]', 'linksurl')
                ->setType('url')
                ->setValue('')
                ->setText('EDITOR_LINKURL')
                ->setIcon('external-link-alt')
                ->setSize('lg') ?>
    </div>
    <div class="row py-2">
        <?php $theView->textInput('links[text]', 'linkstext')
                ->setText('EDITOR_LINKTXT')
                ->setIcon('keyboard')
                ->setSize('lg'); ?>
    </div>   
    <div class="row py-2">            
        <?php $theView->select('links[target]', 'linkstarget')->setOptions($targets)->setText('EDITOR_LINKTARGET')->setIcon('window-restore')->setSize('lg'); ?>
    </div>
    <?php if (count($cssClasses)) : ?>
    <div class="row py-2">            
        <?php $theView->select('links[css]', 'linkscss')->setOptions($cssClasses)->setText('EDITOR_CSS_CLASS')->setIcon('paint-roller')->setSize('lg'); ?>
    </div>
    <?php endif; ?>
</div>

<!-- Bild einfügen -->  
<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertimage">
    <div class="row py-2">
        <?php $theView->textInput('images[path]', 'imagespath')
                ->setType('url')
                ->setValue('')
                ->setText('EDITOR_IMGPATH')
                ->setIcon('image')
                ->setSize('lg'); ?>
    </div>
    <div class="row py-2">
        <?php $theView->textInput('images[alt]', 'imagesalt')
                ->setText('EDITOR_IMGALTTXT')
                ->setIcon('keyboard')
                ->setSize('lg'); ?>
    </div>   
    <div class="row py-2">            
        <?php $theView->select('images[align]', 'imagesalign')->setOptions($aligns)->setText('EDITOR_IMGALIGN')->setIcon('align-center')->setSize('lg'); ?>
    </div>
    <?php if (count($cssClasses)) : ?>
    <div class="row py-2">            
        <?php $theView->select('images[css]', 'imagescss')->setOptions($cssClasses)->setText('EDITOR_CSS_CLASS')->setIcon('paint-roller')->setSize('lg'); ?>
    </div>
    <?php endif; ?>
</div>

<!-- Tabelle einfügen -->  
<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-inserttable">
    <div class="row py-2">
        <?php $theView->textInput('table[rows]', 'tablerows')
                ->setValue(1)->setMaxlenght(5)
                ->setText('EDITOR_INSERTTABLE_ROWS')
                ->setIcon('arrow-down')
                ->setSize('lg'); ?>
    </div>
    <div class="row py-2">
        <?php $theView->textInput('table[rows]', 'tablecols')
                ->setValue(1)->setMaxlenght(5)
                ->setText('EDITOR_INSERTTABLE_COLS')
                ->setIcon('arrow-right')
                ->setSize('lg'); ?>
    </div>
</div>

<!-- Liste einfügen -->  
<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertlist">
    <div class="row py-2">
        <?php $theView->textInput('list[rows]', 'listrows')
                ->setValue(1)->setMaxlenght(5)
                ->setText('EDITOR_INSERTTABLE_ROWS')
                ->setIcon('keyboard')
                ->setSize('lg'); ?>
    </div>
    <div class="row py-2">
        <?php $theView->textInput('list[type]', 'listtype')
                ->setValue('')
                ->setText('EDITOR_INSERTLIST_TYPESIGN')
                ->setIcon('list-ul')
                ->setSize('lg'); ?>
    </div>
</div>

<!-- Player einfügen -->  
<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertmedia">
    
    <div class="row py-2">
        <div class="col-12 col-md-8 px-0">
            <div class="row">
                <?php $theView->textInput('media[path]', 'mediapath')
                        ->setType('url')
                        ->setValue('')
                        ->setText('EDITOR_IMGPATH')
                        ->setIcon('film ')
                        ->setSize('lg'); ?>
            </div>
        </div>
        <div class="col fpcm-ui-align-center">
            <?php $theView->icon('photo-video')->setText('EDITOR_INSERTMEDIA_FORMAT_SELECT')->setIconOnly(true)->setSize('lg'); ?>
        </div>
        <div class="col-11 col-md-3 mt-2 mt-md-0">
            <?php $theView->select('media[format]', 'mediaformat')->setOptions($playerFormats)->setClass('fpcm-editor-mediaformat')->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div> 
    
    <div class="row py-2">
        <div class="col-12 col-md-8 px-0">
            <div class="row">
                <?php $theView->textInput('media[path]', 'mediapath2')
                        ->setType('url')
                        ->setValue('')
                        ->setText('EDITOR_IMGPATH_ALT')
                        ->setIcon('file-video')
                        ->setSize('lg'); ?>
            </div>
        </div>
        <div class="col fpcm-ui-align-center">
            <?php $theView->icon('photo-video')->setText('EDITOR_INSERTMEDIA_FORMAT_SELECT')->setIconOnly(true)->setSize('lg'); ?>
        </div>
        <div class="col-11 col-md-3 mt-2 mt-md-0">
            <?php $theView->select('media[format2]', 'mediaformat2')->setOptions($playerFormats)->setClass('fpcm-editor-mediaformat')->setFirstOption(fpcm\view\helper\select::FIRST_OPTION_DISABLED); ?>
        </div>
    </div> 
    
    <div class="row py-2">
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
        <div class="col-1 col-md-auto">
            <?php $theView->button('insertposterimg', 'insertposterimg')->setText('HL_FILES_MNG')->setIcon('image')->setIconOnly(true); ?>
        </div>
    </div> 
    
    <div class="row py-2">        
        <div class="col-12 fpcm-ui-center">
            <div id="fpcm-ui-editor-media-controlgroup">
                <?php $theView->radiobutton('mediatype', 'mediatypea')->setText('EDITOR_INSERTMEDIA_AUDIO')->setClass('fpcm-editor-mediatype')->setValue('audio')->setSelected(true); ?>
                <?php $theView->radiobutton('mediatype', 'mediatypev')->setText('EDITOR_INSERTMEDIA_VIDEO')->setClass('fpcm-editor-mediatype')->setValue('video'); ?>
                <?php $theView->checkbox('controls', 'controls')->setText('EDITOR_INSERTMEDIA_CONTROLS')->setValue(1)->setSelected(1); ?>
                <?php $theView->checkbox('autoplay', 'autoplay')->setText('EDITOR_INSERTMEDIA_AUTOPLAY')->setValue(1); ?>
            </div>
        </div>
    </div>
    
    <div class="row g-0 align-self-center align-content-center justify-content-center" id="fpcm-dialog-editor-html-insertmedia-preview"></div>
</div>

<!-- Farben einfügen -->
<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertcolor">
    
    <div class="row py-2">
        <?php $theView->textInput('colorhexcode')
                ->setValue('#000000')
                ->setType('color')
                ->setMaxlenght(7)
                ->setText('EDITOR_INSERTCOLOR_HEXCODE')
                ->setIcon('eye-dropper')
                ->setSize('lg')
                ->setDisplaySizes(['12', '10'], ['12', '2']); ?>
    </div>    

    <div class="row g-0 py-2">
        <div class="col-12 fpcm-dialog-editor-colors fpcm-ui-center fpcm-ui-editor-metabox"></div>
    </div>
    
    <div class="row g-0 py-2">
        <div class="col-12 py-2 fpcm-ui-center">
            <div id="fpcm-ui-editor-color-controlgroup">
                <?php $theView->radiobutton('color_mode', 'color_mode1')->setText('EDITOR_INSERTCOLOR_TEXT')->setClass('fpcm-ui-editor-colormode')->setValue('color')->setSelected(true); ?>
                <?php $theView->radiobutton('color_mode', 'color_mode2')->setText('EDITOR_INSERTCOLOR_BACKGROUND')->setClass('fpcm-ui-editor-colormode')->setValue('background'); ?>
            </div>
        </div>
    </div>
</div>

<!-- Smiley einfügen -->
<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertsmileys"></div>

<!-- Symbol einfügen -->
<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertsymbol"></div>

<!-- Vorlage einfügen -->
<div class="fpcm ui-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertquote">
    <div class="row g-0 py-2">
        <div class="col-12 fpcm-ui-padding-md-bottom">
            <label for="quotetext">
                <?php $theView->icon('keyboard')->setSize('lg'); ?>
                <?php $theView->write('EDITOR_HTML_BUTTONS_QUOTE_TEXT'); ?>:
            </label>
        </div>
        <div class="col-12 fpcm-ui-padding-md-bottom"><?php $theView->textarea('quote[text]')->setPlaceholder(true)->setText('EDITOR_HTML_BUTTONS_QUOTE')->setClass('fpcm ui-full-width ui-textarea-medium ui-textarea-noresize'); ?></div>
    </div>
    <div class="row py-2">
        <?php $theView->textInput('quote[src]')
                ->setValue('')
                ->setText('TEMPLATE_ARTICLE_SOURCES')
                ->setIcon('external-link-alt')
                ->setSize('lg'); ?>
    </div>   
    <div class="row g-0 py-2">
        <div class="col-12 py-2 fpcm-ui-center">
            <div id="fpcm-ui-editor-quote-controlgroup">
                <?php $theView->radiobutton('quote[type]', 'quotetype1')->setText('EDITOR_HTML_BUTTONS_QUOTE_BLOCK')->setClass('fpcm-ui-editor-quotemode')->setValue('blockquote')->setSelected(true); ?>
                <?php $theView->radiobutton('quote[type]', 'quotetype2')->setText('EDITOR_HTML_BUTTONS_QUOTE_INLINE')->setClass('fpcm-ui-editor-quotemode')->setValue('q'); ?>
            </div>
        </div>
    </div>
</div>