<?php /* @var $theView fpcm\view\viewVars */ ?>
<?php if (!empty($topDescription)) : ?>
<div class="row g-0 pb-2 fpcm ui-background-white-50p">
    <div class="col-12">
        <fieldset>
            <legend><?php $theView->write('GLOBAL_INFO'); ?></legend>
            <p class="mx-2"><?php $theView->write($topDescription); ?></p>
        </fieldset>
    </div>
</div>
<?php endif; ?>

<div id="fpcm-dataview-<?php print $dataViewId; ?>">
    
    <div class="row row-cols-3 placeholder-wave py-2">
        
        <div class="col">
            <span class="placeholder col-7"></span>
        </div>
        
        <div class="col text-center">
            <span class="placeholder col-2"></span>
        </div>
        
        <div class="col">
            <span class="placeholder col-5"></span>
        </div>
        
    </div>
    
    <div class="row row-cols-3 placeholder-wave py-2">
        
        <div class="col">
            <span class="placeholder col-12"></span>
        </div>
        
        <div class="col text-center">
            <span class="placeholder col-5"></span>
        </div>
        
        <div class="col">
            <span class="placeholder col-9"></span>
        </div>
        
    </div>
    
    <div class="row row-cols-3 placeholder-wave py-2">
        
        <div class="col">
            <span class="placeholder col-4"></span>
        </div>
        
        <div class="col text-center">
            <span class="placeholder col-6"></span>
        </div>
        
        <div class="col">
            <span class="placeholder col-7"></span>
        </div>
        
    </div>
    
    <div class="row row-cols-3 placeholder-wave py-2">
        
        <div class="col">
            <span class="placeholder col-6"></span>
        </div>
        
        <div class="col text-center">
            <span class="placeholder col-7"></span>
        </div>
        
        <div class="col">
            <span class="placeholder col-4"></span>
        </div>
        
    </div>
    
    <div class="row row-cols-3 placeholder-wave py-2">
        
        <div class="col">
            <span class="placeholder col-5"></span>
        </div>
        
        <div class="col text-center">
            <span class="placeholder col-3"></span>
        </div>
        
        <div class="col">
            <span class="placeholder col-8"></span>
        </div>
        
    </div>
    
    <div class="row row-cols-3 placeholder-wave py-2">
        
        <div class="col">
            <span class="placeholder col-9"></span>
        </div>
        
        <div class="col text-center">
            <span class="placeholder col-2"></span>
        </div>
        
        <div class="col">
            <span class="placeholder col-4"></span>
        </div>
        
    </div>
    
</div>