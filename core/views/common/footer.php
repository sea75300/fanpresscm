            </div>
        </div>

        <?php if ($theView->formActionTarget && $theView->showPageToken) : ?>
            <?php $theView->pageTokenField('pgtkn'); ?>
        </form>
        <?php endif; ?>
        
        <div class="fpcm-footer fpcm-ui-font-small fpcm-ui-center fpcm-footer-bottom d-md-none">
            <?php include $theView->getIncludePath('common/footer_copy.php'); ?>
        </div>

    </body>
</html>
