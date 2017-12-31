<?php include __DIR__.'/html_dialogs.php'; ?>
<table class="fpcm-ui-table">
    <?php if ($editorMode) : ?>
    <tr>
        <td>
            <div class="fpcm-ui-editor-metabox">
                <?php include dirname(__DIR__).'/times.php'; ?>
                <?php include dirname(__DIR__).'/metainfo.php'; ?>
                <div class="fpcm-clear"></div>
            </div>
        </td>
    </tr>    
    <?php endif; ?>
    <tr>
        <td>
            <?php \fpcm\model\view\helper::textInput('article[title]', 'fpcm-full-width', $article->getTitle()); ?>
        </td>
    </tr>
    <tr>
        <td class="fpcm-ui-editor-categories">
            <?php include dirname(__DIR__).'/categories.php'; ?>
        </td>
    </tr>
    <tr>
        <td class="ui-widget-content ui-corner-all ui-state-normal">

            <div class="fpcm-ui-buttonset fpcm-ui-editor-buttons">
                
                <?php if (count($editorStyles)) : ?>
                <select class="fpcm-ui-input-select" id="fpcm-editor-styles">
                    <option value=""><?php $FPCM_LANG->write('EDITOR_SELECTSTYLES'); ?></option>
                    <?php foreach ($editorStyles as $description => $tag) : ?>
                    <option class="fpcm-editor-select-click fpcm-editor-cssclick" value="<?php print $tag; ?>"><?php print $description; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>

                <select class="fpcm-ui-input-select" id="fpcm-editor-paragraphs">
                    <option value=""><?php $FPCM_LANG->write('EDITOR_PARAGRAPH'); ?></option>
                    <?php foreach ($editorParagraphs as $descr => $tag) : ?>
                    <option class="fpcm-editor-select-click fpcm-editor-htmlclick" value="<?php print $tag; ?>"><?php print $descr; ?></option>
                    <?php endforeach; ?>
                </select>
                
                <select class="fpcm-ui-input-select" id="fpcm-editor-fontsizes">
                    <option value=""><?php $FPCM_LANG->write('EDITOR_SELECTFS'); ?></option>
                    <?php foreach ($editorFontsizes as $editorFontsize) : ?>
                    <option class="fpcm-editor-htmlfontsize" value="<?php print $editorFontsize; ?>"><?php print $editorFontsize; ?>pt</option>
                    <?php endforeach; ?>
                </select>

                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_BOLD'); ?> (Ctrl + B)" id="fpcm-editor-html-bold-btn" class="fpcm-editor-htmlclick" htmltag="b"><span class="fa fa-bold"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_ITALIC'); ?> (Ctrl + I)" id="fpcm-editor-html-italic-btn" class="fpcm-editor-htmlclick" htmltag="i"><span class="fa fa-italic"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_UNDERLINE'); ?> (Ctrl + U)" id="fpcm-editor-html-underline-btn" class="fpcm-editor-htmlclick" htmltag="u"><span class="fa fa-underline"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_STRIKE'); ?> (Ctrl + O)" id="fpcm-editor-html-strike-btn" class="fpcm-editor-htmlclick" htmltag="s"><span class="fa fa-strikethrough"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_INSERTCOLOR'); ?> (Ctrl + Shift + F)" id="fpcm-dialog-editor-html-insertcolor-btn"><span class="fa fa-tint"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_SUP'); ?> (Ctrl + Y)" id="fpcm-editor-html-sup-btn" class="fpcm-editor-htmlclick" htmltag="sup"><span class="fa fa-superscript"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_SUB'); ?> (Ctrl + Shift + Y)" id="fpcm-editor-html-sub-btn" class="fpcm-editor-htmlclick" htmltag="sub"><span class="fa fa-subscript"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_ALEFT'); ?> (Ctrl + Shift + L)" id="fpcm-editor-html-aleft-btn" class="fpcm-editor-alignclick" htmltag="left"><span class="fa fa-align-left"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_ACENTER'); ?> (Ctrl + Shift + C)" id="fpcm-editor-html-acenter-btn" class="fpcm-editor-alignclick" htmltag="center"><span class="fa fa-align-center"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_ARIGHT'); ?> (Ctrl + Shift + R)" id="fpcm-editor-html-aright-btn" class="fpcm-editor-alignclick" htmltag="right"><span class="fa fa-align-right"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_AJUSTIFY'); ?> (Ctrl + Shift + J)" id="fpcm-editor-html-ajustify-btn" class="fpcm-editor-alignclick" htmltag="justify"><span class="fa fa-align-justify"></span></button>            
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_LISTUL'); ?> (Ctrl + Alt + N)" id="fpcm-editor-html-insertlist-btn"><span class="fa fa-list-ul"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_LISTOL'); ?> (Ctrl + Shift + N)" id="fpcm-editor-html-insertlistnum-btn"><span class="fa fa-list-ol"></span></button>            
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_QUOTE'); ?> (Ctrl + Shift + Q)" id="fpcm-editor-html-quote-btn" class="fpcm-editor-htmlclick" htmltag="blockquote"><span class="fa fa-quote-left"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_INSERTLINK'); ?>  (Ctrl + L)" id="fpcm-dialog-editor-html-insertlink-btn"><span class="fa fa-link"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_INSERTPIC'); ?>  (Ctrl + P)" id="fpcm-dialog-editor-html-insertimage-btn"><span class="fa fa-picture-o"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_INSERTMEDIA'); ?> (Ctrl + Shift + Z)" id="fpcm-dialog-editor-html-insertmedia-btn"><span class="fa fa-youtube-play"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_IFRAME'); ?> (Ctrl + F)" id="fpcm-editor-html-insertiframe-btn"><span class="fa fa-puzzle-piece"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_READMORE'); ?> (Ctrl + M)" id="fpcm-editor-html-insertmore-btn"><span class="fa fa-plus-square"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_INSERTTABLE'); ?> (Ctrl + Shift + T)" id="fpcm-dialog-editor-html-inserttable-btn"><span class="fa fa-table"></span></button>
                <button title="<?php $FPCM_LANG->write('HL_OPTIONS_SMILEYS'); ?> (Ctrl + Shift + E)" id="fpcm-dialog-editor-html-insertsmiley-btn"><span class="fa fa-smile-o"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_ARTICLETPL'); ?> (Ctrl + Shift + D)" id="fpcm-dialog-editor-html-insertdraft-btn"><span class="fa fa-file-text-o"></span></button>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_SYMBOL'); ?> (Ctrl + Shift + I)" id="fpcm-dialog-editor-html-insertsymbol-btn"><span class="fa fa-font"></span></button>
            <?php if (count($extraButtons)) : ?>
                <?php foreach ($extraButtons as $extraButton)  : ?>
                <button title="<?php print $extraButton['title']; ?>" class="fpcm-editor-htmlclick <?php print $extraButton['class']; ?>" htmltag="<?php print $extraButton['htmltag']; ?>" id="fpcm-dialog-editor-html-<?php print $extraButton['id']; ?>-btn"><span class="<?php print $extraButton['icon']; ?>"></span></button>
                <?php endforeach; ?>
            <?php endif; ?>
                <button title="<?php $FPCM_LANG->write('EDITOR_HTML_BUTTONS_REMOVESTYLE'); ?> (Ctrl + Shift + S)" id="fpcm-editor-html-removetags-btn"><span class="fa fa-eraser"></span></button>
                <button disabled="disabled" title="<?php $FPCM_LANG->write('EDITOR_AUTOSAVE_RESTORE'); ?>" id="fpcm-editor-html-restoredraft-btn"><span class="fa fa-repeat fa-flip-horizontal"></span></button>
            </div>                
        </td>
    </tr>
    <tr>
        <td style="font-size: <?php print $editorDefaultFontsize; ?>">
            <?php \fpcm\model\view\helper::textArea('article[content]', 'fpcm-full-width', $article->getContent()) ?>
        </td>
    </tr>
</table>