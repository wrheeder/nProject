<?php

class Model_Vendor extends Model_Table {

    public $entity_code = 'vendor';

    function init() {
        parent::init();
        $this->addField('Dostawca')->mandatory('Vendor is Required')->type('Wrap');
    }

    function getVendorID($vendor) {
        $this->tryLoadBy('Dostawca', $vendor);
        if ($this->Loaded()) {
            return $this->id;
        } else {
            $this->set('Dostawca', $vendor);
            $this->save();
            return $this->id;
        }
    }

}