<?php

class Application_Model_DbTable_UsuarioHasPerfil extends Zend_Db_Table_Abstract
{

  protected $_name = 'smo_usuario_has_perfil';

  public function perfilesUsuario($id_usuario)
  {
    $id = (int) $id_usuario;
    $select = $this->select()->setIntegrityCheck(false);
    $select->from(array('p' => 'smo_perfil'), array('p.*'));
    $select->join(array('uhp' => 'smo_usuario_has_perfil'), 'uhp.per_id_perfil = p.per_id_perfil', array());
    $select->where('usu_id_usuario = ?', $id);

   $row = $this->fetchAll($select);
    if (!$row) {
    return false;
    }
    return $row;
  }
  
  public function addUhp($idPerfil,$idUsuario)
  {
      $data = array(
            'per_id_perfil' => $idPerfil,
            'usu_id_usuario' => $idUsuario,
        );
        $this->insert($data);
  }
  
  public function deleteUhp($idUsuario){
        $this->delete('usu_id_usuario = ' . (int)$idUsuario);
  }
}