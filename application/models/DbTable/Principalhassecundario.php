<?php

class Application_Model_DbTable_Principalhassecundario extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_principal_has_secundario';

    public function addPrincipalHasSecundario($formData){
      $data = array(
            'dop_id_documento_principal'    => $formData['id_principal'],
            'dos_id_documento_secundario'    => $formData['id_secundario']
        );
        //var_dump($data);
      $this->insert($data);
    }
    
    public function listarRegistros($doc_id_prin=''){
        $sql    = $this->select();
        
        if($doc_id_prin!=''){
            $sql->where("dop_id_documento_principal = ?", $doc_id_prin);
        }
        
        $results    = $this->fetchAll($sql);
        
        return $results;
    }
}

