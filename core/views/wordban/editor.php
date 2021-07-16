<?php /* @var $theView fpcm\view\viewVars */ ?>
<div class="row">
    <div class="col-12">
        <fieldset class="my-3">
            
            <div class="row">
                <?php $theView->textInput('wbitem[searchtext]')
                    ->setValue($item->getSearchtext())
                    ->setText('WORDBAN_NAME')
                    ->setIcon('filter'); ?>
            </div>

            <div class="row">
                <?php $theView->textInput('wbitem[replacementtext]')
                    ->setValue($item->getReplacementtext())
                    ->setText('WORDBAN_REPLACEMENT_TEXT')
                    ->setIcon('edit'); ?>
            </div>


            <div class="row">
                
                <div class="col-form-label col-12 col-sm-6 col-md-3">
                    <?php $theView->icon('cogs'); ?> <span class="fpcm-ui-label ps-1"> <?php $theView->write('GLOBAL_ACTION_PERFORM'); ?></span>
                </div>
                
                <div class=" col-12 col-sm-6 col-md-9">                        
                    <?php $theView->checkbox('wbitem[replacetxt]')->setText('WORDBAN_REPLACETEXT')->setSelected($item->getReplaceTxt())->setSwitch(true); ?>
                    <?php $theView->checkbox('wbitem[lockarticle]')->setText('WORDBAN_APPROVE_ARTICLE')->setSelected($item->getLockArticle())->setSwitch(true); ?>
                    <?php $theView->checkbox('wbitem[commentapproval]')->setText('WORDBAN_APPROVA_COMMENT')->setSelected($item->getCommentApproval())->setSwitch(true); ?>       
                </div>
                
            </div>

        </fieldset>
    </div>
</div>
