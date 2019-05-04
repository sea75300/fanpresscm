            </div>
        </div>

        <?php if ($theView->formActionTarget && $theView->showPageToken) : ?>
            <?php $theView->pageTokenField('pgtkn'); ?>
            <?php $theView->hiddenInput('activeTab')->setValue((isset($activeTab) ? $activeTab : 0)); ?>
        </form>
        <?php endif; ?>
        
        <div class="row no-gutters fpcm-ui-margin-lg-top">
            <div class="col-12 fpcm-ui-font-small fpcm-ui-center fpcm-ui-background-white-50p fpcm-ui-padding-md-tb">
                <b><?php $theView->write('VERSION'); ?>:</b> <?php print $theView->version; ?><br>
                &copy; 2011-<?php print date('Y'); ?> <a href="https://nobody-knows.org/download/fanpress-cm/" target="_blank" rel="noreferrer,noopener,external">nobody-knows.org</a>                
            </div>
        </div>

        <?php if ($theView->loggedIn) : ?><?php fpcmDebugOutput(); ?><?php endif; ?>

    </body>
</html>
