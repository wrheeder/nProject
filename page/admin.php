<?php

class page_admin extends Page_ApplicationPage {

    function init() {
        if (!$this->api->auth->isAdmin()) {
            $this->api->redirect('index');
        }
        parent::init();
        $tabs = $this->add('Tabs');
        $crud = $tabs->addTab('Users')->add("CRUD");
        $crud->setModel('User', array('username', 'email', 'isAdmin', 'isManualAdd', 'isManualEdit', 'isManualDelete', 'isCommentUpdate', 'isUpload'));
        $crud->api->stickyGet('id');
        // $crud1 = $tabs->addTab('Upload Fields')->add('CRUD');
        // $crud1->setModel('UploadFields');
        // $crud2 = $tabs->addTab('Owner')->add("CRUD");
        // $crud2->api->stickyGet('id');
        // $crud2->setModel('Owner');
        // $crud3 = $tabs->addTab('DefGroup')->add("CRUD");
        // $crud3->api->stickyGet('id');
        // $crud3->setModel('DefGroup');
        // $crud4 = $tabs->addTab('Author')->add("CRUD");
        // $crud4->api->stickyGet('id');
        // $crud4->setModel('Author');
        // $crud5 = $tabs->addTab('Region')->add("CRUD");
        // $crud5->api->stickyGet('id');
        // $crud5->setModel('Region');
        // $crud6 = $tabs->addTab('Vendor')->add("CRUD");
        // $crud6->api->stickyGet('id');
        // $crud6->setModel('Vendor');
        if ($crud->grid) {
            $crud->grid->getColumn('username')->makeSortable();
            $crud->grid->dq->order('username asc');
            $crud->grid->addClass("zebra bordered");
            $crud->grid->addPaginator(10);

            $crud->grid->addColumn('grid/popup', 'changePassword');
        }
        if ($crud->form) {
            //$crud->form->addField('password','password');
            if ($crud->form->isSubmitted()) {
                $m = $crud->form->getModel();
                if ($m['password'] == null || $m['password'] == '')
                    $m->set('password', $this->api->auth->encryptPassword('tempPW1234'));
                $m->save();
            }
        }
//        if ($crud1->grid) {
//            $crud1->grid->addClass("zebra bordered");
//            $crud1->grid->addPaginator(10);
//        }
//
//        if ($crud2->grid) {
//            $crud2->grid->addClass("zebra bordered");
//            $crud2->grid->addPaginator(10);
//        }
//        if ($crud3->grid) {
//            $crud3->grid->addClass("zebra bordered");
//            $crud3->grid->addPaginator(10);
//        }
//        if ($crud4->grid) {
//            $crud4->grid->addClass("zebra bordered");
//            $crud4->grid->addPaginator(10);
//        }
//        if ($crud5->grid) {
//            $crud5->grid->addClass("zebra bordered");
//            $crud5->grid->addPaginator(10);
//        }
//        if ($crud6->grid) {
//            $crud6->grid->addClass("zebra bordered");
//            $crud6->grid->addPaginator(10);
//        }

        $crud7 = $tabs->addTab('Comment Defs')->add("CRUD");
        $crud7->setModel('CommentDef');
        if ($crud7->grid) {
            $crud7->grid->addClass("zebra bordered");
            $crud7->grid->dq->order('def asc');
            $crud7->grid->addPaginator(10);
        }


        $crud8 = $tabs->addTab('Subcontractor')->add("CRUD");
        $crud8->api->stickyGet('id');
        $crud8->setModel('Subco');


        if ($crud8->grid) {
            $crud8->grid->addColumn('expander', 'SubcoDL', 'Distribution List');
            $crud8->grid->getColumn('subco')->makeSortable();
            //$crud8->grid->dq->order('subco asc');
            $crud8->grid->addClass("zebra bordered");
            $crud8->grid->addPaginator(10);
        }

        $crud9 = $tabs->addTab('PO->Subcon')->add("CRUD");
        $crud9->setModel('POSubco');

        if ($crud9->grid) {
            $crud9->grid->addQuickSearch(array('PO'));
            $crud9->grid->controller->importField('po');
            //$crud9->grid->dq->order('po asc');
            $crud9->grid->getColumn('po')->makeSortable();
            $crud9->grid->getColumn('subco')->makeSortable();
            $crud9->grid->addClass("zebra bordered");

            $crud9->grid->addPaginator(10);
            $crud9->grid->addOrder()->move('po', 'first')->now();
        }
        $crud10 = $tabs->addTab('Owner')->add("CRUD");
        $crud10->setModel('TTOwner');

        if ($crud10->grid) {
            $crud10->grid->addClass("zebra bordered");
            $crud10->grid->addPaginator(10);
        }
    }

}