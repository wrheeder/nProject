<?php

class page_uploader extends Page_ApplicationPage {

    function init() {
        if (!$this->api->auth->isUploadManager()) {
            $this->api->redirect('index');
        }
        parent::init();
        $v = $this->add('View_Columns');
        $g = $v->addColumn(6);
        $g->add('H3')->set('Upload N!Project xls');

        $f = $g->add('Form');
        
        $upl1 = $f->addField('upload', 'File')->validateNotNull();

        $fs = $upl1->setModel('filestore/File');
        //$fs->debug();
        $fs->setVolume('TroubleTickets');
        $process = $f->addSubmit('Process file');
        $f->js('submit',"document.body.style.cursor = 'wait'");
        $info1 = $f->add('View_Info');
        $info1->set('Summary of upload :');
        $upl_flds_site = $this->add('Model_UploadFields')->addCondition('model', 'site');
        $upl_flds_tts = $this->add('Model_UploadFields')->addCondition('model', 'troubleticket');
        $g2 = $v->addColumn(6);
        $g2->add('H3')->set('Upload Subcontractor Mapping xls');
        $f2 = $g2->add('Form');
        $upl2 = $f2->addField('upload', 'File')->validateNotNull();
        $f2->addSubmit('Process file');
        $fs1 = $upl2->setModel('filestore/File');
        $fs1->setVolume('IPM');
        
        //$upl->js('change',$process->js(true)->removeAttr('disabled')->attr('aria-disabled','false')->removeClass('ui-button-disabled ui-state-disabled'));
        if ($f->isSubmitted()) {
            
            $fs->tryLoad($upl1->form->data);
            $furl = './' . $fs['dirname'] . '/' . $fs['filename'];
//            $fh = fopen($furl, "rb");
//            $data = fread($fh, filesize($furl));
//
//            fclose($fh);
            $data = file_get_contents($furl);
            
            $site = $this->add('Model_Site');
            $owner = $this->add('Model_Owner');
            $tt = $this->add('Model_TroubleTicket');
            $defg = $this->add('Model_DefGroup');
            $auth = $this->add('Model_Author');
            $vendor = $this->add('Model_Vendor');
            $region = $this->add('Model_Region');

            if (file_exists($furl)) {
                $data = preg_replace('/&[^; ]{0,6}.?/e', "((substr('\\0',-1) == ';') ? '\\0' : '&amp;'.substr('\\0',1))", $data);
                $xml = new SimpleXMLElement($data);
                $cols_cnt =22;// $xml->Worksheet->Table->attributes();
                $row_cnt = $xml->Worksheet->Table->Row->count();
                
                
                //echo $cols_cnt.'....'.$row_cnt.'</br>';
                //die(var_dump($xml->Worksheet->Table->Column->count()));
                $k = 0;

                $headers = array();
                $data_out = array();
                for ($j = 0; $j < $row_cnt; $j++) {
                    $row_out = array();
                    if ($j > 0)
                        $row_out = $headers;
                    for ($i = 0; $i < $cols_cnt; $i++) {
                        if ($j == 0) {
                            $data_out[0][$i] = (String) $xml->Worksheet->Table->Row[$j]->Cell[$i]->Data;
                            $headers[(String) $xml->Worksheet->Table->Row[$j]->Cell[$i]->Data] = null;
                        } else {
                            $row_out[$data_out[0][$i]] = (String) $xml->Worksheet->Table->Row[$j]->Cell[$i]->Data;
                        }

                        $k++;
                    }
                    if ($j != 0) {
                        $site->tryLoadBy('csc', $row_out['NrNetWorkS']);
                        $site_out = array();
                        foreach ($upl_flds_site as $fld) {
                            if ($fld['name'] != 'Owner' && $fld['name'] != 'csc') {
                                $site_out[$fld['name']] = $row_out[$fld['name']];
                            } else {
                                $site_out['Owner_id'] = $owner->getOwnerID($row_out['Owner']);
                            }
                        }
                        $site->set($site_out);
                        $site->save();

                        $tt->tryLoadBy('id', $row_out['ID']);
                        $tt_out = array();
                        $tt_out['id']=$row_out['ID'];
                        $tt_out['site_id'] = $site['id'];
                        foreach ($upl_flds_tts as $fld) {
                            if ($fld['name'] != 'ID' && $fld['name'] != 'site_id' && $fld['name'] != 'defgroup_id' && $fld['name'] != 'author_id' && $fld['name'] != 'region_id' && $fld['name'] != 'vendor_id') {
                                if ($row_out[$fld['name']] != '' && $row_out[$fld['name']] != null)
                                    $tt_out[$fld['name']] = $row_out[$fld['name']];
                                //$tt_out['Uwagi']=$row_out['Uwagi'];
                            }
                        }
                        $tt_out['defgroup_id'] = $defg->getDefGroupID($row_out['Kod'], $row_out['Opis']);
                        $tt_out['author_id'] = $auth->getAuthorID($row_out['Odbierajacy']);
                        $tt_out['region_id'] = $region->getRegionID($row_out['Region']);
                        $tt_out['vendor_id'] = $vendor->getVendorID($row_out['Dostawca']);
                         //die(var_dump($row_out));
                        $tt->set($tt_out);
                        $tt->save();
                        //$site_out = array('csc'=>$row_out['NrNetWorkS'],'NrNetWorkS'=>$row_out['NrNetWorkS'],'NrPTC'=>$row_out['NrPTC'],'NazwaStacji'=>$row_out['NazwaStacji'],'NazwaPTC'=>$row_out['NazwaPTC'],'NazwaPTK'=>$row_out['NazwaPTK'],'NE_Urzadzenie'=>$row_out['NE_Urzadzenie'],'TypUrzadzenia'=>$row_out['TypUrzadzenia'],'Owner_id'=>$owner->getOwnerID($row_out['Owner']));
                    }
                }
                unset($data_out[0]);
                //var_dump($data_out);
                // echo $site->getLog();
                $f->removeClass('loading');
            } else {
                exit('Failed to open .xml.');
            }
            $js = array();
            $js[]="document.body.style.cursor = 'auto'";
            $js[]=$info1->js()->html('<div class="atk-notification-text">
    <i class="ui-icon ui-icon-info"></i>Summary of upload :  Upload Complete !</br>'.$j.' - Rows Handled</br>Site Uploaded </br><hr> '.$site->getLog().'<hr></br>TroubleTickets Uploaded </br><hr> '.$tt->getLog().'<hr>
  </div>');
            $js[]=$process->js()->attr("disabled", "disabled");
            $this->js(true,$js)->univ()->successMessage($fs['original_filename'] . ' processed')->execute();
        }

        if ($f2->isSubmitted()) {
            $po = $this->add('Model_POSubco');
            $subco = $this->add('Model_Subco');
            $fs1->tryLoad($upl2->form->data);
            $furl = './' . $fs1['dirname'] . '/' . $fs1['filename'];
            $fh = fopen($furl, "rb");
            $data = fread($fh, filesize($furl));

            fclose($fh);


            $data = str_replace(array("\r\n", "\t"), array("[NEW*LINE]", "[tAbul*Ator]"), $data);
            $rows = explode("[NEW*LINE]", $data);
            $row = 1;
            foreach ($rows as $lines) {
                $cols = explode("[tAbul*Ator]", $lines);
                $po_out = array();
                foreach ($cols as $li) {
                    $po_out[] = $li;
                }
                $po_record = array();
                $po_record['po'] = $po_out[2];
                $po_record['data_wysłania'] = $po_out[4]==''?null:$po_out[4];
                $subco_id=$subco->getSubcoID($po_out[3]);
                $po_record['subco_id'] = $subco_id;
                if ($row > 1) {
                    if ($subco_id != '' && $subco_id != null && $po_record['po']!='' && $po_record['po']!=null) {
                        $po->tryLoad($po_record['po']);
                        //die(var_dump($po_record));
                        $po->set($po_record);
                        $po->save();
                        //echo $row.'</t>';
                    }
                }
                $row++;
                
            }
            $f2->js(true)->univ()->successMessage($fs1['original_filename'] . '(' . count($rows) . ')processed')->execute();
        }
    }
function XML2Array ( $xml , $recursive = false )
{
    if ( ! $recursive )
    {
        $array = simplexml_load_string ( $xml ) ;
    }
    else
    {
        $array = $xml ;
    }
    
    $newArray = array () ;
    $array = ( array ) $array ;
    foreach ( $array as $key => $value )
    {
        $value = ( array ) $value ;
        if ( isset ( $value [ 0 ] ) )
        {
            $newArray [ $key ] = trim ( $value [ 0 ] ) ;
        }
        else
        {
            $newArray [ $key ] = XML2Array ( $value , true ) ;
        }
    }
    return $newArray ;
}
}


//$process->js(true)->removeAttr('disabled')->removeClass('ui-button-disabled ui-state-disabled')

//$fs->loadBy('id',(int)$upl->get());
//             var_dump($fs['id']);