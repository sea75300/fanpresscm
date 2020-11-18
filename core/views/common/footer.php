            </div>

            <?php if ($theView->formActionTarget && $theView->showPageToken) : ?>
                <?php $theView->pageTokenField(); ?>
                <?php $theView->hiddenInput('activeTab')->setValue((isset($activeTab) ? $activeTab : 0)); ?>
            </form>
            <?php endif; ?>

            <div class="row fpcm-ui-margin-lg-top">
                <div class="col-12 col-sm-6 fpcm-ui-align-left fpcm ui-background-white-50p fpcm-ui-padding-lg-tb">
                    &copy; 2011-<?php print date('Y'); ?> <a href="https://nobody-knows.org/download/fanpress-cm/" target="_blank" rel="noreferrer,noopener,external">nobody-knows.org</a>                
                </div>
                <div class="col-12 col-sm-6 fpcm-ui-align-right fpcm ui-background-white-50p fpcm-ui-padding-lg-tb">
                    <b><?php $theView->write('VERSION'); ?>:</b> <?php print $theView->version; ?><br>
                </div>
            </div>

            <?php if ($theView->loggedIn) : ?><?php fpcmDebugOutput(); ?><?php endif; ?>
        
        </div>

        <?php include_once 'jsfilesbtm.php'; ?>
    </body>
</html>
