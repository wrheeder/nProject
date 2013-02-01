<?php

class Model_TroubleTicketExtOpen extends Model_TroubleTicketExt {

    function init() {
        parent::init();
        $this->addCondition('site_id', $_GET['id'])->dsql->where(array('DataUsuniecia is null')); //
        //$this->debug();
    }

}