<?php

class Page_Admin_SubcoDL extends Page_ApplicationPage {

    function init() {
        parent::init();
        $m = $this->add('Model_SubcoDL')->addCondition('subco_id', $_GET['id']);
        $this->api->stickyGet('id');
        $this->add('CRUD')->setModel($m);
    }

}