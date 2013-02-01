<?php
class Page_ttManager_TroubleTickets_Comments extends Page_ApplicationPage{
    function init(){
        parent::init();
		$m=$this->add('Model_Comments')->addCondition('troubleticket_id',$_GET['id']);
		$this->api->stickyGet('id');
		$crud=$this->add('CRUD');
		$crud->setModel($m);
		if($crud->grid)
		{	
			$crud->grid->addClass("zebra bordered");
		}
	}
}