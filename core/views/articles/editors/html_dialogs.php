<!-- Link einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertlink">  
    <table class="fpcm-ui-table">
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_LINKURL'); ?>:</label></td>
            <td><?php \fpcm\model\view\helper::textInput('links[url]', '', 'http://'); ?></td>
        </tr>
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_LINKTXT'); ?>:</label></td>
            <td><?php \fpcm\model\view\helper::textInput('links[text]', '', ''); ?></td>
        </tr>        
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_LINKTARGET'); ?>:</label></td>
            <td>
                <?php \fpcm\model\view\helper::select('links[target]', $targets, ''); ?>
            </td>
        </tr>        
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_CSS_CLASS'); ?>:</label></td>
            <td>
                <?php \fpcm\model\view\helper::select('links[css]', $cssClasses, ''); ?>
            </td>
        </tr>       
    </table>
</div>

<!-- Bild einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertimage">  
    <table class="fpcm-ui-table">
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_IMGPATH'); ?>:</label></td>
            <td><?php \fpcm\model\view\helper::textInput('images[path]', '', 'http://'); ?></td>
        </tr>
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_IMGALTTXT'); ?>:</label></td>
            <td><?php \fpcm\model\view\helper::textInput('images[alt]', '', ''); ?></td>
        </tr>        
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_IMGALIGN'); ?>:</label></td>
            <td>
                <?php \fpcm\model\view\helper::select('images[align]', $aligns, ''); ?>
            </td>
        </tr>         
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_CSS_CLASS'); ?>:</label></td>
            <td>
                <?php \fpcm\model\view\helper::select('images[css]', $cssClasses, ''); ?>             
            </td>
        </tr>       
    </table>
</div>

<!-- Tabelle einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-inserttable">  
    <table class="fpcm-ui-table fpcm-ui-table-insert">
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_INSERTTABLE_ROWS'); ?>:</label></td>
            <td><?php \fpcm\model\view\helper::textInput('table[rows]', '', 1, false, 5, false, false); ?></td>
        </tr>
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_INSERTTABLE_COLS'); ?>:</label></td>
            <td><?php \fpcm\model\view\helper::textInput('table[cols]', '', 1, false, 5, false, false); ?></td>
        </tr>        
    </table>
</div>

<!-- Liste einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertlist">  
    <table class="fpcm-ui-table fpcm-ui-table-insert">
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_INSERTTABLE_ROWS'); ?>:</label></td>
            <td><?php \fpcm\model\view\helper::textInput('list[rows]', '', 1, false, 5, false, false); ?></td>
        </tr>       
    </table>
</div>

<!-- Player einfügen -->  
<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertmedia">  
    <table class="fpcm-ui-table">
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_IMGPATH'); ?>:</label></td>
            <td><?php \fpcm\model\view\helper::textInput('media[path]'); ?></td>
        </tr>   
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_INSERTMEDIA_AUDIO'); ?>:</label></td>
            <td><?php fpcm\model\view\helper::radio('media[type]', 'fpcm-editor-mediatype', 'audio', '', 'mediatype_a', true); ?></td>
        </tr>
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_INSERTMEDIA_VIDEO'); ?>:</label></td>
            <td><?php fpcm\model\view\helper::radio('media[type]', 'fpcm-editor-mediatype', 'video', '', 'mediatype_v', false); ?></td>
        </tr>         
    </table>
</div>

<!-- Tabelle einfügen -->
<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertcolor">  
    <table class="fpcm-ui-table">
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_INSERTCOLOR_HEXCODE'); ?>:</label></td>
            <td><?php \fpcm\model\view\helper::textInput('fpcm-dialog-editor-html-colorhexcode', '', '', false, 5); ?></td>
        </tr>   
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_INSERTCOLOR_TEXT'); ?>:</label></td>
            <td><?php fpcm\model\view\helper::radio('color_mode', 'color_mode', 'color', '', 'color_mode1'); ?></td>
        </tr>
        <tr>
            <td><label><?php $FPCM_LANG->write('EDITOR_INSERTCOLOR_BACKGROUND'); ?>:</label></td>
            <td><?php fpcm\model\view\helper::radio('color_mode', 'color_mode', 'background', '', 'color_mode2', false); ?></td>
        </tr>        
    </table>
</div>

<?php $count = 1; ?>
<!-- Smiley einfügen -->
<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertsmileys"></div>

<!-- Symbol einfügen -->
<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertsymbol">
    <table class="fpcm-ui-table fpcm-ui-editor-smileys">
        <tr>
        <?php for($i=161;$i<=450;$i++) : ?>
            <td><a class="fpcm-editor-htmlsymbol" symbolcode="&#<?php print $i; ?>;" href="#">&#<?php print $i; ?>;</a></td>
            <?php if ($i % 20 == 0) : ?></tr><tr><?php endif; ?>            
        <?php endfor;  ?>            
        </tr>        
    </table>
</div>

<!-- Vorlage einfügen -->
<div class="fpcm-ui-dialog-layer fpcm-hidden fpcm-editor-dialog" id="fpcm-dialog-editor-html-insertdraft">
    <table class="fpcm-ui-table">
        <tr>
            <td><?php \fpcm\model\view\helper::select('tpldraft', $editorTemplatesList, ''); ?></td>
        </tr>
        <tr>
            <td>
                <pre id="fpcm-dialog-editor-html-insertdraft-preview" class="CodeMirror cm-s-fpcm CodeMirror-wrap"></pre>
            </td>
        </tr>
    </table>
</div>