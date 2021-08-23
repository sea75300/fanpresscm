<?php /* @var $theView \fpcm\view\viewVars */ ?>
<?php if (!empty($descriptions['top'])) : ?>
<div class="row g-0  border-5 border-top border-primary">
    <div class="col-12">
        <div class="m-3">
            <?php $theView->button('topDescr')
                ->setText($descriptions['top']['headline'])
                ->setAria(['controls' => 'topDescrCollapse'])
                ->setData(['bs-toggle' => 'collapse', 'bs-target' => '#topDescrCollapse'])
                ->setIcon('chevron-down')
                ->setPrimary(); ?>

            <div class="collapse mt-3" id="topDescrCollapse">
                <div class="card card-body">
                    <div class="row g-0">
                        <div class="col-12">
                            <?php $theView->write($descriptions['top']['text']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>            
      
<div class="row">
    <div class="col-12 col-md-6">
        <fieldset>            
            <?php foreach ($fields as $option => $field) : ?>
            <div class="row my-3">
                <?php print $field; ?>
            </div>
            <?php endforeach; ?>
        </fieldset>
    </div>
</div>

<?php if (!empty($descriptions['buttom'])) : ?>
<div class="row g-0">
    <div class="col-12">
        <div class="m-3">
            
            <?php $theView->button('topDescr')
                ->setText($descriptions['buttom']['headline'])
                ->setAria(['controls' => 'bottomDescrCollapse'])
                ->setData(['bs-toggle' => 'collapse', 'bs-target' => '#bottomDescrCollapse'])
                ->setIcon('chevron-down')
                ->setPrimary(); ?>

            <div class="collapse mt-3" id="bottomDescrCollapse">
                <div class="card card-body">
                    <div class="row g-0">
                        <div class="col-12">
                            <?php $theView->write($descriptions['buttom']['text']); ?>
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</div>
<?php endif; ?>   