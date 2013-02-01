<?php

class Model_CustModel extends Model_Table {

    function init() {
        parent::init();
    }

    function group_concat($field, $distinct = false) {
        if (!is_object($field))
            $field = $this->getElement($field);
        $q = $this->dsql()->del('fields');
        if (!$distinct) {
            $q->field($q->expr('group_concat([s_field])')->setCustom('s_field', $field));
        } else {
            $q->field($q->expr('group_concat(DISTINCT [s_field])')->setCustom('s_field', $field));
        }
        return $q;
    }

}