<?php include $theView->getIncludePath('articles/editors/html_dialogs.php'); ?>
<table class="fpcm-ui-table">
    <?php if ($editorMode) : ?>
    <tr>
        <td>
            <div class="fpcm-ui-editor-metabox">
                <?php include $theView->getIncludePath('articles/times.php'); ?>
                <?php include $theView->getIncludePath('articles/metainfo.php'); ?>
                <div class="fpcm-clear"></div>
            </div>
        </td>
    </tr>    
    <?php endif; ?>
    <tr>
        <td>
            <?php $theView->textInput('article[title]')->setClass('fpcm-full-width')->setValue($article->getTitle()); ?>
        </td>
    </tr>
    <tr>
        <td class="fpcm-ui-editor-categories">
            <?php $fieldname = 'article[categories][]'; ?>
            <?php include $theView->getIncludePath('articles/categories.php'); ?>
        </td>
    </tr>
    <tr>
        <td class="ui-widget-content ui-corner-all ui-state-normal">

            <div class="fpcm-ui-controlgroup fpcm-ui-editor-buttons">
                
                <?php if (count($editorStyles)) : ?>
                <select class="fpcm-ui-input-select" id="fpcm-editor-styles">
                    <option value=""><?php $theView->write('EDITOR_SELECTSTYLES'); ?></option>
                    <?php foreach ($editorStyles as $description => $tag) : ?>
                    <option class="fpcm-editor-select-click fpcm-editor-cssclick" value="<?php print $tag; ?>"><?php print $description; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>

                <select class="fpcm-ui-input-select" id="fpcm-editor-paragraphs">
                    <option value=""><?php $theView->write('EDITOR_PARAGRAPH'); ?></option>
                    <?php foreach ($editorParagraphs as $descr => $tag) : ?>
                    <option class="fpcm-editor-select-click fpcm-editor-html-click" value="<?php print $tag; ?>"><?php print $descr; ?></option>
                    <?php endforeach; ?>
                </select>
                
                <select class="fpcm-ui-input-select" id="fpcm-editor-fontsizes">
                    <option value=""><?php $theView->write('EDITOR_SELECTFS'); ?></option>
                    <?php foreach ($editorFontsizes as $editorFontsize) : ?>
                    <option class="fpcm-editor-htmlfontsize" value="<?php print $editorFontsize; ?>"><?php print $editorFontsize; ?>pt</option>
                    <?php endforeach; ?>
                </select>

                <?php foreach ($editorButtons as $editorButton) : ?>
                    <?php print $editorButton->setClass('fpcm-editor-html-click')->setIconOnly(true); ?>
                <?php endforeach; ?>
            </div>                
        </td>
    </tr>
    <tr>
        <td style="font-size: <?php print $editorDefaultFontsize; ?>">
            <?php $theView->textarea('article[content]')->setClass('fpcm-full-width')->setValue($article->getContent(), ENT_QUOTES); ?>
        </td>
    </tr>
</table>