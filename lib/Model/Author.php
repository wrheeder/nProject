<?php

class Model_Author extends Model_Table {

    public $entity_code = 'author';

    function init() {
        parent::init();
        $this->addField('Author')->mandatory('Author is Required');
    }

    function getAuthorID($author) {
        $this->tryLoadBy('Author', $author);
        if ($this->Loaded()) {
            return $this->id;
        } else {
            $this->set('Author', $author);
            $this->save();
            return $this->id;
        }
    }

}