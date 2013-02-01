<?php

class Model_Site extends Model_Table {

    public $table = 'site';
    public $entity_owner = 'owner';
    public $records_updated = 0;
    public $records_added = 0;
    public $upload_log = '';

    function init() {
        parent::init();
        //$this->debug();
        set_time_limit(0);
        
        $this->addField('csc');
        $this->addField('NrNetWorkS');
        $this->addField('NrPTC');
        $this->addField('NazwaStacji');
        $this->addField('NazwaPTC');
        $this->addField('NazwaPTK');
        $this->addField('NE_Urzadzenie');
        $this->addField('TypUrzadzenia');
        $this->addField('DataZgloszenia')->type('DateTime')->Caption('DataZgloszeniaUsunieciaPL');
        $this->hasOne('Owner', 'Owner_id', 'Owner')->mandatory('Owner is Required');
        $this->hasMany('TroubleTicket');
        
        $this->getField('csc')->system(true);
        
        
        $this->addExpression('TT_list')->set($this->refSQL('TroubleTicket')->group_concat('id'))->type('text');
        $this->addExpression('PO_list')->set($this->refSQL('TroubleTicket')->group_concat('Zamowienie', true))->type('text');
        $this->addCondition('TT_list','<>','null');
        
        $this->addHook('beforeSave', $this);
    }

    function beforeSave($m) {

        if ($m->loaded()) {
            //$this->upload_log.='Updating</br>';
            $this->records_updated++;
        } else {
            //$this->upload_log.='Adding</br>';
            $this->records_added++;
        }
    }

    function getLog() {
        $this->upload_log = 'Updated ' . $this->records_updated . ' sites</br>Created ' . $this->records_added . ' sites</br>';
        return $this->upload_log;
    }

}