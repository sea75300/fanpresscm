            </div>

            <?php if ($theView->formActionTarget && $theView->showPageToken) : ?>
                <?php $theView->pageTokenField(); ?>
                <?php $theView->hiddenInput('activeTab')->setValue((isset($activeTab) ? $activeTab : 0)); ?>
            </form>
            <?php endif; ?>

            <?php if ($theView->loggedIn) : ?><?php fpcmDebugOutput(); ?><?php endif; ?>
            
            <div class="row row-cols-1 row-cols-md-2 py-2 bg-dark text-light fs-6">
                <div class="col bg-dark">
                    &copy; 2011-<?php print date('Y'); ?> <a class="text-light" href="https://nobody-knows.org/download/fanpress-cm/" target="_blank" rel="noreferrer,noopener,external">nobody-knows.org</a>                
                </div>
                <div class="col">
                    <div class="d-flex justify-content-md-end">
                        <b><?php $theView->write('VERSION'); ?>:</b>&nbsp;<?php print $theView->version; ?>                        
                    </div>
                </div>
            </div>

        </div>

        <?php include_once 'jsfilesbtm.php'; ?>
    </body>
</html>
