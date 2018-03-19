            </div>

            <div id="fpcm-footer-bottom" class="col-12  align-self-end fpcm-footer fpcm-ui-font-small fpcm-ui-center fpcm-footer-bottom fpcm-ui-background-white-50p">
                <?php include $theView->getIncludePath('common/footer_copy.php'); ?>
            </div>

        </div>

        <?php if ($theView->formActionTarget) : ?>
            <?php $theView->pageTokenField('pgtkn'); ?>
        </form>
        <?php endif; ?>

    </body>
</html>
