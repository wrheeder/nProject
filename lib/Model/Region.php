<?php

class Model_Region extends Model_Table{
    public $entity_code = 'region';
    function init(){
        parent::init();
        $this->addField('Region')->mandatory('Region is Required');
    }
    function getRegionID($region){
        $this->tryLoadBy('Region',$region);
        if($this->Loaded()){
            return $this->id;
        }
        else{
            $this->set('Region',$region);
            $this->save();
            return $this->id;
        }
    }
}