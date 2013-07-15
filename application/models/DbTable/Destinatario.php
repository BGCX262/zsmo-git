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
    
    public function indexMiniDestinatario()
    {
        $select = $this->select()
                  ->from( array("smo_gde_destinatario"), array("des_id_destinatario", "des_rut", "des_nombre", "des_ciudad", "des_tipo", "des_telefono") )
                  ->where("des_desactivado = ?","NO");
        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("No se encontraron filas.");
        }
        return $row->toArray();
    }
    
    public function addDestinatario($formData){
        //var_dump($formData);
      $this->insert($formData);
      $lastInsertId = $this->getAdapter()->lastInsertId();
      return $lastInsertId;
    }

    public function updateDestinatario($formData){
        $data = array(
              "des_nombre"          => $formData['des_nombre'],
              "des_rut"             => $formData['des_rut'],
              'des_direccion'       => $formData['des_direccion'],
              'des_ciudad'          => $formData['des_ciudad'],
              'des_telefono'        => $formData['des_telefono'],
              'des_tipo'            => $formData['des_tipo'],
              'des_comuna'          => $formData['des_comuna'],
              'des_region'          => $formData['des_region'],
              'des_contacto'        => $formData['des_contacto'] 
            );
        //var_dump($data);
        $this->update($data, 'des_id_destinatario = ' . (int)$formData['des_id_destinatario']);
    }
    
    public function deleteDestinatario($id_destinatario){
        $data = array(
          "des_desactivado"          => 'SI' );
        
        $this->update($data, 'des_id_destinatario = ' . (int)$id_destinatario);
    }
}