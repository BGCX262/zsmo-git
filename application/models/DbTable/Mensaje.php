<?php

class Application_Model_DbTable_Mensaje extends Zend_Db_Table_Abstract
{

    protected $_name = 'smo_mensaje';

  public function getIndexMensaje(){
        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("m"=>"smo_mensaje"), array("m.men_mensaje","m.men_id_mensaje","m.men_fecha") )
               ->join( array("u"=>"smo_usuario"),"m.usu_id_usuario = u.usu_id_usuario", array("u.usu_nombre","u.usu_apellido_1") );
        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("No se encontraron filas.");
        }
        return $row->toArray();
  }

  public function getMensajePortada(){
        $select = $this->select()->setIntegrityCheck(false);
        $select->from( array("m"=>"smo_mensaje"), array("m.*") )
          ->limit(4,0)
          ->order( array("men_id_mensaje DESC") );
    
        $row = $this->fetchAll($select);
        if (!$row) {
            throw new Exception("No se encontraron filas.");
        }
        return $row->toArray();
  }
  
  public function getMensaje($id) {
        $id = (int)$id;
        $row = $this->fetchRow('men_id_mensaje = ' . $id);
        if (!$row) {
            throw new Exception("No se encontró fila $id");
        }
        return $row->toArray();
  }
  
  public function addMensaje($men_fecha, $men_mensaje, $usu_id_usuario){
      $data = array(
            'men_fecha'                      => $men_fecha,
            'men_mensaje'                    => $men_mensaje,
            'usu_id_usuario'                 => $usu_id_usuario,

        );
      $this->insert($data);
  }
  
  public function updateMensaje($men_id_mensaje, $men_fecha, $men_mensaje, $usu_id_usuario){
      $data = array(
            'men_fecha'                      => $men_fecha,
            'men_mensaje'                    => $men_mensaje,
            'usu_id_usuario'                 => $usu_id_usuario,

        );
      $this->update($data, 'men_id_mensaje = ' . (int)$men_id_mensaje);
  }
  
    public function deleteMensaje($men_id_mensaje){
        $this->delete('men_id_mensaje = ' . (int)$men_id_mensaje);
    }  
  
}
