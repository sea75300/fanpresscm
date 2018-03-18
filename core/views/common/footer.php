            </div>

            <div class="fpcm-ui-clear"></div>

            <div id="fpcm-footer-bottom" class="col-12 fpcm-footer fpcm-ui-font-small fpcm-ui-center fpcm-footer-bottom fpcm-ui-background-white-50p">
                <b><?php $theView->write('VERSION'); ?>:</b> <?php print $theView->version; ?><br>
                &copy; 2011-<?php print date('Y'); ?> <a href="https://nobody-knows.org/download/fanpress-cm/" target="_blank">nobody-knows.org</a>
            </div>

        </div>

        <?php if ($theView->formActionTarget) : ?>
            <?php $theView->pageTokenField('pgtkn'); ?>
        </form>
        <?php endif; ?>

    </body>
</html>
