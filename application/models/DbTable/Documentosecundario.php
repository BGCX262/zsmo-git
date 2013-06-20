<?php

class Application_Model_DbTable_Documentosecundario extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_documento_secundario';

    public function addDocumentoSecundario($formData){
      $data['dos_numero_identificador'] = $formData;

        //var_dump($data);
      $this->insert($data);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }
    
    public function getDocumentosecundario($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('dos_id_documento_secundario = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }

}

