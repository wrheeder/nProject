<?php

class Model_SubcoDL extends Model_Table {

    public $entity_code = 'subco_dl';

    function init() {
        parent::init();
        $this->hasOne('Subco', 'subco_id', 'subco')->mandatory('Subcontractor required');
        $this->addField('email', 'email');
        $this->addHook('beforeSave', $this);
    }

    function beforeSave() {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        if (preg_match($regex, $this['email'])) {
            
        } else {
            throw $this->exception('Not A Valid Email', 'ValidityCheck')->setField('email');
        }
        
    }

}