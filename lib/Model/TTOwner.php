<?php

class Model_TTOwner extends Model_Table{
    public $table = 'ttowner';
    function init(){
        parent::init();
        $this->addField('ttowner')->Caption('Wlasciciel');
    }
}