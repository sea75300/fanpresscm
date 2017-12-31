<table class="fpcm-ui-table">
    <tr>
        <?php $tmpArticle = $article; ?>
        <?php $article    = $revisionArticle; ?>
        <td class="fpcm-half-width">
            <div class="fpcm-ui-editor-metabox">
                <?php include dirname(__DIR__).'/times.php'; ?>
                <?php include dirname(__DIR__).'/metainfo.php'; ?>
                <div class="fpcm-clear"></div>
            </div>
        </td>
        <?php
            $article    = $tmpArticle;
            $tmpArticle = null;
        ?>
        <td class="fpcm-half-width">
            <div class="fpcm-ui-editor-metabox">
                <?php include dirname(__DIR__).'/times.php'; ?>
                <?php include dirname(__DIR__).'/metainfo.php'; ?>
                <div class="fpcm-clear"></div>
            </div>
        </td>
    </tr>    
    <tr>
        <td>
            <h3><?php print fpcm\model\view\helper::escapeVal($revisionArticle->getTitle()); ?></h3>
        </td>
        <td>
            <h3><?php print fpcm\model\view\helper::escapeVal($article->getTitle()); ?></h3>
        </td>
    </tr>
    <tr>
        <td>
            <div class="fpcm-ui-buttonset fpcm-ui-editor-categories fpcm-ui-editor-categories-revisiondiff">
                <?php foreach ($categories as $value => $key) : ?>
                <?php $selected = in_array($value, $revisionArticle->getCategories()); ?>
                <?php fpcm\model\view\helper::checkbox('article[categories][revision]', '', $value, $key->getName(), 'rcat'.$value, $selected); ?>
                <?php endforeach; ?>
            </div>
        </td>
        <td>
            <div class="fpcm-ui-buttonset fpcm-ui-editor-categories fpcm-ui-editor-categories-revisiondiff">
                <?php foreach ($categories as $value => $key) : ?>
                <?php $selected = in_array($value, $article->getCategories()); ?>
                <?php fpcm\model\view\helper::checkbox('article[categories][current]', '', $value, $key->getName(), 'ccat'.$value, $selected); ?>
                <?php endforeach; ?>
            </div> 
        </td>
    </tr>
    <tr>
        <td class="fpcm-ui-editor-contentdiff-right" colspan="2">
            <?php print html_entity_decode($textDiff); ?>
        </td>
    </tr>
</table>