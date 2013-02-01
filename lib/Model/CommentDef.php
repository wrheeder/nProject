<?php
 class Model_CommentDef extends Model_Table{
    public $table = 'def';
    function init(){
        parent::init();
			$this->addField('def');
	}
}