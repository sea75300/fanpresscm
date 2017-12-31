<script type="text/javascript">
    var fpcmActionPath      = '<?php print $FPCM_BASEMODULELINK; ?>';
    var fpcmAjaxActionPath  = '<?php print $FPCM_BASEMODULELINK; ?>ajax/';
<?php if (isset($FPCM_JS_VARS) && is_array($FPCM_JS_VARS) && count($FPCM_JS_VARS)) : ?>        
    <?php foreach ($FPCM_JS_VARS AS $jsvarname => $jsvarValue) : ?>            
    var <?php print $jsvarname; ?> = <?php print (is_array($jsvarValue) || is_object($jsvarValue)) ? json_encode($jsvarValue) : ((is_bool($jsvarValue) || is_numeric($jsvarValue)) ? $jsvarValue : "'$jsvarValue'") ; ?>;
    <?php endforeach; ?>
<?php endif; ?>
</script>