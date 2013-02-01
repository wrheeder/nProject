<?php

class page_ttManager extends Page_ApplicationPage {

    function init() {
        parent::init();
        $v = $this->add('View_Columns');
        $c = $v->addColumn(12);
        $crud_sites = $c->add("CRUD");
        $m = $this->api->auth->model;
        if (!$m['isManualAdd']) {
            $crud_sites->allow_add = false;
        }
        if (!$m['isManualEdit']) {
            $crud_sites->allow_edit = false;
        }
        if (!$m['isManualDelete']) {
            $crud_sites->allow_del = false;
        }
        $crud_sites->setModel('Site');
        if ($crud_sites->grid) {
            $crud_sites->grid->addClass("zebra bordered");
            $crud_sites->grid->addPaginator(10)->addQuickSearch(array('csc', 'owner', 'TT_list', 'PO_List'));
            $crud_sites->grid->addColumn('expander', 'TroubleTickets', 'TT\'s');
            $crud_sites->grid->dq->order('NrNetWorkS asc');
        }
    }

}