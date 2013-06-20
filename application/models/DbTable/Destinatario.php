<?php

class Application_Model_DbTable_Destinatario extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_gde_destinatario';

    function getPorTipoDestinatario($tipo){ //$tipo = INTERNO/PROVEEDOR/CLIENTE
      $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("d"=>"smo_gde_destinatario"), array("d.des_id_destinatario", "d.des_nombre") )
              ->where("d.des_tipo = '".$tipo."'")
              ->order("d.des_nombre ASC");
        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("No se encontraron filas.");
        }
        return $row;
    }
    
    function getDestinatarioPorId($id_destinatario){ //ID del Destinatario -> Ej: 43
      $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("d"=>"smo_gde_destinatario"), array("d.*") )
              ->where("d.des_id_destinatario = '".$id_destinatario."'");
        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("No se encontraron filas.");
        }
        return $row;

    }
    
    function getDestinatarioPorNombre($nombre){ //ID del Destinatario -> Ej: 43
      $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("d"=>"smo_gde_destinatario"), array("d.*") )
              ->where("d.des_nombre = ?",$nombre);
        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("No se encontraron filas.");
        }
        return $row;

    }
    
    public function getDestinatario($id){
        $id = (int)$id;
        $row = $this->fetchRow('des_id_destinatario = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
    }
}