<?php

class Page_ttManager_TroubleTickets extends Page_ApplicationPage {

    function init() {
        parent::init();
        $tabs = $this->add('Tabs');
        $model = $this->add('Model_TroubleTicketExt')->addCondition('site_id', $_GET['id']);
        $model_open = $this->add('Model_TroubleTicketExtOpen');
        $model_closed = $this->add('Model_TroubleTicketExtClosed');
        $this->api->stickyGet('id');
        $crud_all = $tabs->addTab('All')->add('CRUD');
        
        
        $crud_open = $tabs->addTab('Open')->add('CRUD',array('allow_add'=>false,'allow_edit'=>false,'allow_del'=>false));
        
        $crud_closed = $tabs->addTab('Closed')->add('CRUD',array('allow_add'=>false,'allow_edit'=>false,'allow_del'=>false));
        
        
        $crud_all->api->stickyGet('id');
        $m = $this->api->auth->model;
        if (!$m['isManualAdd']) {
            $crud_all->allow_add = false;
        }

        if (!$m['isManualEdit']) {
            $crud_all->allow_edit = false;
        }
        if (!$m['isManualDelete']) {
            $crud_all->allow_del = false;
        }

        $crud_all->setModel($model);
        $crud_open->setModel($model_open);
        $crud_closed->setModel($model_closed);
        if ($m['isCommentUpdate']) {
            if ($crud_all->grid) {
                $crud_all->grid->addColumn('expander', 'Comments', 'Comments');
            }
            
        }

        if ($crud_all->grid) {
            $f = $crud_all->add('Form', 'mailform');
            $f_checked = $f->addField('line', 'checked');
            $this->js(true)->_selector('.atk-form-row-line ')->hide();

            $crud_all->grid->controller->importField('id');
            // $crud->grid->getColumn('Subco');
            $crud_all->grid->addOrder()->move('id', 'first')->now();
            $crud_all->grid->addOrder()->move('data_wyslania', 'after', 'DataWpisu')->now();
            $crud_all->grid->addClass("zebra bordered");
            // $crud->grid->addPaginator(5);
            $crud_all->grid->addSelectable($f_checked);
            $url = $this->api->url('ttManager/TroubleTickets/Mailer', array('checked' => 'tralala'));

            $SUB = $f->addSubmit('mail');

            if ($f->isSubmitted()) {
                $test = $f->get('checked');
                $url->set('checked', $test);
                $f->js()->univ()->frameURL('Mail TTs', $url)->execute();
            }
        }
        if ($crud_open->grid) {
            $f = $crud_open->add('Form', 'mailform');
            $f_checked = $f->addField('line', 'checked');
            $this->js(true)->_selector('.atk-form-row-line ')->hide();

            $crud_open->grid->controller->importField('id');
            $crud_open->grid->controller->importField('data_wyslania');
            $crud_open->grid->addOrder()->move('id', 'first')->now();
            $crud_open->grid->addOrder()->move('data_wyslania', 'after', 'DataWpisu')->now();
            $crud_open->grid->addClass("zebra bordered");
            // $crud->grid->addPaginator(5);
            $crud_open->grid->addSelectable($f_checked);
            $url = $this->api->url('ttManager/TroubleTickets/Mailer', array('checked' => 'tralala'));

            $SUB = $f->addSubmit('mail');

            if ($f->isSubmitted()) {
                $test = $f->get('checked');
                $url->set('checked', $test);
                $f->js()->univ()->frameURL('Mail TTs', $url)->execute();
            }
        }
        if ($crud_closed->grid) {
            $crud_closed->grid->controller->importField('id');
            $crud_closed->grid->controller->importField('data_wyslania');
            $crud_closed->grid->addOrder()->move('id', 'first')->now();
            $crud_closed->grid->addOrder()->move('data_wyslania', 'after', 'DataWpisu')->now();
            $crud_closed->grid->addClass("zebra bordered");
        }
    }

}