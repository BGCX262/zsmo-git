<?php

class Application_Model_DbTable_Documentohasdestinatario extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_destinatario_has_documento';

    public function addDocumentohasdestinatario($formData){
      $data = array(
            'dop_id_documento_principal'    => $formData['dop_id_documento_principal'],
            'des_id_destinatario'           => $formData['des_id_destinatario'],
            'dhdo_tipo'                     => $formData['dhdo_tipo']
        );
       // var_dump($data);
      $this->insert($data);
    }
    
    public function listarRegistros($doc_id=''){
        $sql    = $this->select();
        
        if($doc_id!=''){
            $sql->where("dop_id_documento_principal = ?", $doc_id);
        }
        
        $results    = $this->fetchAll($sql);
        
        return $results;
    }
}

