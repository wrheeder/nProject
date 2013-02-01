<?php

class Model_Comments extends Model_Table {

    public $table = 'comments';

    function init() {
        parent::init();
        $this->addField('comment');
        //$this->debug();
        $this->hasOne('TroubleTicket', 'troubleticket_id', false);
        $this->hasOne('CommentDef', 'def_id', 'def');

        $this->addField('made_by')
                ->visible(true)
                ->editable(false);
        $this->addHook('beforeSave', $this);
        $this->addHook('beforeInsert', $this);
    }

    function beforeSave() {
        if ($this->Loaded()) {
            $this['made_by'] = "[" . $this->api->auth->get('username') . '] Updated at ' . date('Y-m-d H:i:s');
        }
    }

    function beforeInsert($q,$m){
        $m->set('made_by',"[" . $this->api->auth->get('username') . '] Commented at ' . date('Y-m-d H:i:s'));
    }
}