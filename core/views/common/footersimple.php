<?php if ($theView->navigation && $theView->loggedIn) : ?>
            </div>
        </div>
<?php endif; ?>
        
        <?php if ($theView->formActionTarget && $theView->showPageToken) : ?>
            <?php $theView->pageTokenField(); ?>
        </form>
        <?php endif; ?>

        <?php include_once 'jsfilesbtm.php'; ?>
    </body>
</html>
