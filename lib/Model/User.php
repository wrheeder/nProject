<?php

class Model_User extends Model_Table {

    public $entity_code = 'user';

    function init() {
        parent::init();
        $this->addField('username')->mandatory('Username required');
        $this->addField('email')->mandatory('Email required');
        $this->addField('isAdmin')->type('boolean')->caption('Administrator');
        $this->addField('isManualAdd')->type('boolean')->caption('Alow Adding');
        $this->addField('isManualEdit')->type('boolean')->caption('Alow Edit');
        $this->addField('isManualDelete')->type('boolean')->caption('Alow Delete');
        $this->addField('isCommentUpdate')->type('boolean')->caption('Can Comment');
        $this->addField('isFileStore')->type('boolean')->hidden();
        $this->addField('isUpload')->type('boolean')->caption('Alow Upload');
        $this->addField('password')->display(array('form' => 'password'))->mandatory('Type your password');
        
    }

}