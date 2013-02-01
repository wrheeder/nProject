<?php

class Model_POSubco extends Model_Table {

    public $entity_code = 'po_subco';
    public $id_field = 'po';

    function init() {
        parent::init();
        $this->getField('po')
        ->visible(true)
        ->editable(true)
        ->caption('PO')->mandatory('PO Required');
        $subco=$this->hasOne('subco', 'subco_id', 'subco')->mandatory('Subcontractor required');
        $this->addHook('beforeInsert',$this);
    }
    function beforeInsert(){
        $po = $this->get('po');
        $this->tryloadBy('po',$po);
        if($this->Loaded()){
           throw $this->exception('This PO already exists','ValidityCheck')->setField('po');
        }
    }
    

}