<?php /* @var $theView \fpcm\view\viewVars */ ?>
<div class="p-3">
    
    <?php $theView->alert('info')->setText((string) $xml->description); ?>
    
    <div class="accordion" id="fpcm-id-twitter-accordion">
        
        <?php foreach ($xml->step as $value) : ?>

        <div class="accordion-item">
            <h2 class="accordion-header" id="fpcm-id-twitter-step-<?php print (string) $value->attributes()['id']; ?>">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#fpcm-id-twitter-item-<?php print (string) $value->attributes()['id']; ?>" aria-expanded="true" aria-controls="collapseOne">
                    <?php $theView->escape( (string) $value->attributes()['title'] ); ?>
                </button>
            </h2>
            <div id="fpcm-id-twitter-item-<?php print (string) $value->attributes()['id']; ?>" class="accordion-collapse collapse" aria-labelledby="fpcm-id-twitter-step-<?php print (string) $value->attributes()['id']; ?>" data-bs-parent="#fpcm-id-twitter-accordion">
                <div class="accordion-body">
                    
                    <div class="row">
                        
                        <div class="col flex-grow-1 align-self-center justify-content-center">
                            <?php print (string) $value; ?>
                        </div>
                        <div class="col-auto align-self-center justify-content-center">
                            
                            <?php if (isset($value->attributes()['btn-href']) && isset($value->attributes()['btn-descr'])) : ?>
                            
                            
                            <?php $theView->linkButton( 'open-step-' . (string) $value->attributes()['id'] )
                                    ->setUrl( (string) $value->attributes()['btn-href'] )
                                    ->setText( (string) $value->attributes()['btn-descr'] )
                                    ->setIcon('arrow-up-right-from-square')
                                    ->setTarget('_blank'); ?>
                            
                            
                            <?php endif; ?>
                            
                            
                        </div>
                        
                    </div>
                    
                    
                </div>
            </div>
        </div>
        <?php endforeach;; ?>



    </div>
</div>

