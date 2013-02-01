<?php

class Model_TroubleTicketExt extends Model_CustModel {

    public $table = 'troubletickets';

    function init() {
        parent::init();
        set_time_limit(0);

        $this->addField('Uwagi')->type('Wrap');
        $this->addField('Kategoria');
        $this->addField('DataWpisu')->type('DateTime')->Caption('ZgloszeniePL');
        $this->addField('DataUsuniecia')->type('DateTime')->Caption('DataZamknieciaUsterek');
        $this->addField('OsobaKontaktowa')->type('Wrap');
        $this->addField('data_wyslania')->type('DateTime')->Caption('DataWyslaniaDoSUB');

        $this->hasOne('Site', 'site_id', false);
        $def_group = $this->join('defgroup');
        $def_group->addField('kod');
        $def_group->addField('opis')->type('Wrap');
        $subpo = $this->join('po_subco.po', 'zamowienie', 'left');
        $this->addField('zamowienie')
                ->visible(true)
                ->editable(true);
//        ->caption('Zamowienie');
        // $subco = $subpo->join('subco.id', 'subco_id','left');
        // $subco->addField('subco');

        $subco = $subpo->hasOne('Subco', 'subco_id', 'subco')->visible(true)
                        ->editable(false)->system(true);
        $this->hasOne('Author', 'author_id', 'Author')->mandatory('Author Required');
        $this->hasOne('Vendor', 'vendor_id', 'Dostawca')->mandatory('Vendor Required')->type('Wrap');
        $this->hasOne('Region', 'region_id', 'Region')->mandatory('Region Required');
        $this->hasOne('TTOwner', 'ttowner_id', 'ttowner')->Caption('Wlasciciel')->type('Wrap');
    }

}