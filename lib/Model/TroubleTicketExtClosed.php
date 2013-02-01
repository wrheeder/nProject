<?php

class Model_TroubleTicketExtClosed extends Model_TroubleTicketExt {

    function init() {
        parent::init();
        $this->addCondition('site_id', $_GET['id'])->dsql->where(array('DataUsuniecia is not null')); //
        //$this->debug();
    }

}