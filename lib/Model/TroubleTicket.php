<?php

class Model_TroubleTicket extends Model_CustModel {

    public $table = 'troubletickets';
    public $records_updated = 0;
    public $records_added = 0;
    public $upload_log = '';

    function init() {
        parent::init();
        set_time_limit(0);
        
        $this->addField('Uwagi')->type('Wrap');
        $this->addField('Kategoria');
        $this->addField('Zamowienie');
        $this->addField('DataWpisu')->type('DateTime')->Caption('ZgloszeniePL');
        $this->addField('DataUsuniecia')->type('DateTime')->Caption('DataZamknieciaUsterek');
        $this->addField('OsobaKontaktowa')->type('Wrap');
        $this->addField('data_wyslania')->type('DateTime')->Caption('DataWyslaniaDoSUB');
        
        $this->hasOne('Site', 'site_id', false);
        $this->hasOne('DefGroup', 'defgroup_id', 'kod');
        $this->hasOne('Author', 'author_id', 'Author')->mandatory('Author Required');
        $this->hasOne('Vendor', 'vendor_id', 'Dostawca')->mandatory('Vendor Required');
        $this->hasOne('Region', 'region_id', 'Region')->mandatory('Region Required');
        $this->hasOne('TTOwner', 'ttowner_id', 'ttowner');
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
        $this->upload_log = 'Updated ' . $this->records_updated . ' TroubleTickets</br>Created ' . $this->records_added . ' TroubleTickets</br>';
        return $this->upload_log;
    }

}